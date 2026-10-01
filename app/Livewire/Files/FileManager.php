<?php

namespace App\Livewire\Files;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class FileManager extends Component
{
    use WithFileUploads;

    /** Per-user storage cap in bytes (2 GB default). */
    public const QUOTA = 2 * 1024 * 1024 * 1024;

    #[Url]
    public ?int $folderId = null;

    public string $search = '';
    public array $uploads = [];
    public string $newFolderName = '';
    public bool $showNewFolder = false;

    public function updatedUploads(): void
    {
        $this->validate([
            'uploads.*' => ['file', 'max:20480'], // 20 MB per file
        ]);

        $used = File::where('user_id', auth()->id())->sum('size');

        foreach ($this->uploads as $upload) {
            if ($used + $upload->getSize() > self::QUOTA) {
                $this->addError('uploads', 'Storage quota exceeded — delete some files first.');
                break;
            }

            $path = $upload->store('files/'.auth()->id(), 'local');

            File::create([
                'name'      => $upload->getClientOriginalName(),
                'path'      => $path,
                'mime'      => $upload->getMimeType(),
                'size'      => $upload->getSize(),
                'folder_id' => $this->folderId,
                'user_id'   => auth()->id(),
            ]);

            $used += $upload->getSize();
        }

        $this->uploads = [];
    }

    public function createFolder(): void
    {
        $this->validate(['newFolderName' => ['required', 'string', 'max:100']]);

        Folder::create(['name' => $this->newFolderName, 'user_id' => auth()->id()]);
        $this->reset('newFolderName', 'showNewFolder');
    }

    public function deleteFolder(int $id): void
    {
        $folder = Folder::where('user_id', auth()->id())->findOrFail($id);

        foreach ($folder->files as $file) {
            Storage::disk('local')->delete($file->path);
            $file->delete();
        }
        $folder->delete();

        if ($this->folderId === $id) {
            $this->folderId = null;
        }
    }

    public function download(int $id)
    {
        $file = File::where('user_id', auth()->id())->findOrFail($id);

        return Storage::disk('local')->download($file->path, $file->name);
    }

    public function deleteFile(int $id): void
    {
        $file = File::where('user_id', auth()->id())->findOrFail($id);
        Storage::disk('local')->delete($file->path);
        $file->delete();
    }

    public function render()
    {
        $files = File::where('user_id', auth()->id())
            ->where('folder_id', $this->folderId)
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->get();

        $used = (float) File::where('user_id', auth()->id())->sum('size');

        return view('livewire.files.file-manager', [
            'folders'     => Folder::where('user_id', auth()->id())->withCount('files')->orderBy('name')->get(),
            'files'       => $files,
            'usedBytes'   => $used,
            'usedPercent' => min(100, round($used / self::QUOTA * 100, 1)),
            'quotaGb'     => self::QUOTA / (1024 ** 3),
        ])->title('File Manager');
    }
}
