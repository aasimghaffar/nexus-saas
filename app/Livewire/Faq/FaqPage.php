<?php

namespace App\Livewire\Faq;

use App\Models\Faq;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class FaqPage extends Component
{
    public string $search = '';

    public bool $showForm = false;
    public ?int $editingId = null;
    public string $category = 'General';
    public string $question = '';
    public string $answer = '';
    public bool $published = true;

    public function create(): void
    {
        $this->authorize('settings.manage');
        $this->reset('editingId', 'question', 'answer');
        $this->category = 'General';
        $this->published = true;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $this->authorize('settings.manage');
        $faq = Faq::findOrFail($id);
        $this->editingId = $faq->id;
        $this->category = $faq->category;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
        $this->published = $faq->published;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->authorize('settings.manage');
        $data = $this->validate([
            'category'  => ['required', 'string', 'max:60'],
            'question'  => ['required', 'string', 'max:255'],
            'answer'    => ['required', 'string', 'max:5000'],
            'published' => ['boolean'],
        ]);

        $this->editingId
            ? Faq::findOrFail($this->editingId)->update($data)
            : Faq::create($data + ['position' => (Faq::max('position') ?? 0) + 1]);

        $this->reset('showForm', 'editingId');
    }

    public function delete(int $id): void
    {
        $this->authorize('settings.manage');
        Faq::findOrFail($id)->delete();
        $this->reset('showForm', 'editingId');
    }

    public function render()
    {
        $canManage = auth()->user()->can('settings.manage');

        $faqs = Faq::query()
            ->when(! $canManage, fn ($q) => $q->where('published', true))
            ->when($this->search, fn ($q) => $q->where(fn ($q) => $q
                ->where('question', 'like', "%{$this->search}%")
                ->orWhere('answer', 'like', "%{$this->search}%")))
            ->orderBy('position')
            ->get()
            ->groupBy('category');

        return view('livewire.faq.faq-page', ['groups' => $faqs, 'canManage' => $canManage])
            ->title('Help Center & FAQ');
    }
}
