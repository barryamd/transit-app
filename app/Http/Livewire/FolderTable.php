<?php

namespace App\Http\Livewire;

use App\LivewireTables\DataTableComponent;
use App\LivewireTables\Views\Column\DateColumn;
use App\Models\Folder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class FolderTable extends DataTableComponent
{
    protected $model = Folder::class;
    protected array $createButtonParams = [
        'title' => 'Nouveau dossier',
        'route' => 'folders.create',
        'permission' => 'create-folder',
    ];
    public string|null $status = null;
    public int|null $customerId = null;

    public function mount()
    {
        $this->authorize('view-folder');

        $user = Auth::user();
        if ($user->customer) {
            $this->customerId = $user->customer->id;
        }
    }

    public function columns(): array
    {
        return [
            Column::make('number')->hideIf(true)->searchable(),
            LinkColumn::make("Numero du dossier")
                ->title(fn($row) => $row->number)
                ->location(fn($row) => route('folders.show', $row))
                ->sortable(),
            Column::make("Numero CNT", "num_cnt")
                ->sortable(),
            Column::make("Poids total", "weight")
                ->sortable(),
            Column::make("Port", "harbor")
                ->sortable(),
            Column::make("Status", "status")
                ->sortable()->hideIf((bool)$this->status),
            DateColumn::make("Date d'ouverture", "created_at")
                ->sortable(),
            Column::make("Client", "customer.nif")
                ->format(fn($value, $row) => $row->customer->user->full_name),
            Column::make('Actions', 'id')
                ->format(fn($value, $row) => view('folders.action-buttons',
                    ['row' => $row, 'status' => $this->status])),
        ];
    }

    public function builder(): Builder
    {
        return Folder::query()->with('customer.user')->select('folders.*')
            ->when($this->status, fn(Builder $query, $status) => $query->where('status', '=', $status))
            ->when($this->customerId, fn(Builder $query, $customerId) => $query->where('customer_id', '=', $customerId));
    }
}
