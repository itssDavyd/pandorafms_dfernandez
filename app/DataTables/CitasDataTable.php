<?php

namespace App\DataTables;

use App\Models\Cita;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CitasDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))->setRowId('id');
    }

    public function query(Cita $model): QueryBuilder
    {
        if (Auth::id() === 1) {
            //Soy admin veo todo.
            return $model->orderBy('id')->newQuery();
        } else {
            return $model::where("id_user", Auth::id())->orderBy('id')->newQuery();
        }
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('citas-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
//                Button::make('add'),
//                Button::make('excel'),
//                Button::make('csv'),
//                Button::make('pdf'),
//                Button::make('print'),
//                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('user_name')->title("Nombre"),
            Column::make('user_email')->title("Email"),
            Column::make('user_dni')->title("DNI"),
            Column::make('user_telefono')->title("Teléfono"),
            Column::make('tipo')->title("Tipo"),
            Column::make('date')->title("Fecha"),
            Column::make('hour')->title("Hora")

        ];
    }

    protected function filename(): string
    {
        return 'Citas_' . date('YmdHis');
    }
}
