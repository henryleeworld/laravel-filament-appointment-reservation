<?php

namespace App\Livewire;

use App\Models\Reservation;
use App\Models\User;
use App\Services\ReservationService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Component;

class ReservationForm extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?array $data = [];

    protected ReservationService $service;

    public function __construct()
    {
        $this->service = new ReservationService();
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $form): Schema
    {
        $dateFormat = 'Y-m-d';

        return $form
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required(),
                TextInput::make('email')
                    ->label(__('Email'))
                    ->email()
                    ->unique('users', 'email')
                    ->required(),
                DatePicker::make('date')
                    ->label(__('Date'))
                    ->native(false)
                    ->minDate(now()->format($dateFormat))
                    ->maxDate(now()->addWeeks(2)->format($dateFormat))
                    ->format($dateFormat)
                    ->required()
                    ->live(),
                Radio::make('track')
                    ->label(__('Track'))
                    ->options(fn (Get $get) => $this->service->getAvailableTimesForDate($get('date')))
                    ->hidden(fn (Get $get) => ! $get('date'))
                    ->required(),
            ])
            ->statePath('data')
            ->model(Reservation::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $date             = Carbon::parse($data['date']);
        [$trackId, $hour] = explode('-', $data['track']);
        $startTime        = $date->copy()->hour((int)$hour);
        $endTime          = $startTime->copy()->addHour();
        $dateTimeFormat   = 'Y-m-d H:i:s';

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => '',
        ]);

        $record = Reservation::create([
            'user_id'    => $user->id,
            'track_id'   => $trackId,
            'start_time' => $startTime->format($dateTimeFormat),
            'end_time'   => $endTime->format($dateTimeFormat),
        ]);

        $this->form->model($record)->saveRelationships();

        Notification::make()
            ->success()
            ->title(__('Reservation has been created'))
            ->seconds(5)
            ->send();

        // Reset form
        $this->form->fill();
    }

    public function render(): View
    {
        return view('livewire.reservation-form');
    }
}
