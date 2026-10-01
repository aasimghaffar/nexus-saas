<?php

namespace App\Livewire\Calendar;

use App\Models\Event;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class CalendarPage extends Component
{
    #[Url]
    public string $month = ''; // YYYY-MM

    public bool $showForm = false;
    public ?int $editingId = null;
    public string $title = '';
    public string $description = '';
    public string $date = '';
    public string $startTime = '09:00';
    public string $endTime = '10:00';
    public bool $allDay = false;
    public string $color = 'brand';

    public function mount(): void
    {
        $this->month = $this->month ?: now()->format('Y-m');
    }

    public function previousMonth(): void
    {
        $this->month = Carbon::parse($this->month.'-01')->subMonth()->format('Y-m');
    }

    public function nextMonth(): void
    {
        $this->month = Carbon::parse($this->month.'-01')->addMonth()->format('Y-m');
    }

    public function today(): void
    {
        $this->month = now()->format('Y-m');
    }

    public function create(string $date): void
    {
        $this->reset('editingId', 'title', 'description', 'allDay');
        $this->date = $date;
        $this->startTime = '09:00';
        $this->endTime = '10:00';
        $this->color = 'brand';
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $event = Event::where('user_id', auth()->id())->findOrFail($id);
        $this->editingId = $event->id;
        $this->title = $event->title;
        $this->description = (string) $event->description;
        $this->date = $event->starts_at->format('Y-m-d');
        $this->startTime = $event->starts_at->format('H:i');
        $this->endTime = ($event->ends_at ?? $event->starts_at->addHour())->format('H:i');
        $this->allDay = $event->all_day;
        $this->color = $event->color;
        $this->showForm = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'title'       => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'date'        => ['required', 'date'],
            'startTime'   => ['required', 'date_format:H:i'],
            'endTime'     => ['required', 'date_format:H:i'],
            'allDay'      => ['boolean'],
            'color'       => ['required', 'in:brand,emerald,amber,rose,sky,violet'],
        ]);

        $payload = [
            'title'       => $data['title'],
            'description' => $data['description'],
            'starts_at'   => Carbon::parse("{$data['date']} {$data['startTime']}"),
            'ends_at'     => Carbon::parse("{$data['date']} {$data['endTime']}"),
            'all_day'     => $data['allDay'],
            'color'       => $data['color'],
        ];

        if ($this->editingId) {
            Event::where('user_id', auth()->id())->findOrFail($this->editingId)->update($payload);
        } else {
            Event::create($payload + ['user_id' => auth()->id()]);
        }

        $this->reset('showForm', 'editingId');
    }

    public function deleteEvent(int $id): void
    {
        Event::where('user_id', auth()->id())->findOrFail($id)->delete();
        $this->reset('showForm', 'editingId');
    }

    public function render()
    {
        $first = Carbon::parse($this->month.'-01');
        $gridStart = $first->copy()->startOfWeek(Carbon::MONDAY);
        $gridEnd = $first->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $events = Event::where('user_id', auth()->id())
            ->whereBetween('starts_at', [$gridStart, $gridEnd->copy()->endOfDay()])
            ->orderBy('starts_at')
            ->get()
            ->groupBy(fn (Event $e) => $e->starts_at->format('Y-m-d'));

        $weeks = [];
        for ($day = $gridStart->copy(); $day <= $gridEnd; $day->addDay()) {
            $weeks[$day->format('o-W')][] = $day->copy();
        }

        return view('livewire.calendar.calendar-page', [
            'first'  => $first,
            'weeks'  => array_values($weeks),
            'events' => $events,
        ])->title('Calendar');
    }
}
