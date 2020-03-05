<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Table definition for {{ $tables->first()->getConnection()->getDatabaseName() }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" href="/vendor/fontawesome-free/css/all.min.css">
    <style>
        .fas {
            color: rgba(0, 0, 0, 0.5);
        }

        table.table {
            white-space: nowrap;
        }

        div.table ~ div.table {
            margin-top: 3rem;
        }

        div.table h2 {
            margin: 2rem 0;
        }

        .is-null {
            color: rgba(0, 0, 0, 0.5);
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="container-fluid">

    <h1 id="top">{{ $tables->first()->getConnection()->getDatabaseName() }}</h1>

    <ol>
        @foreach($tables as $table)
            <li>
                <a href="#{{ $table->TABLE_NAME }}">{{ $table->TABLE_NAME }}</a>
                @if ($table->is_intermediate_table)
                    <span>中間</span>
                @else
                    <span>{{ $table->TABLE_COMMENT }}</span>
                @endif
            </li>
        @endforeach
    </ol>

    @foreach($tables as $table)

        <div class="row table">
            <div class="col">

                <div class="row">
                    <div class="col">
                        <h2 id="{{ $table->TABLE_NAME }}">
                            {{ $table->TABLE_NAME }}
                            <small>{{ $table->TABLE_COMMENT }}</small>
                        </h2>
                    </div>
                    <div class="col text-right">
                        <a href="#top">TOP</a>
                    </div>
                </div>

                <table class="table table-bordered table-sm">
                    <thead class="thead-dark">
                    <tr>
                        <th width="15%">COLUMN NAME</th>
                        <th width="20%">COMMENT</th>
                        <th width="15%">DATA TYPE</th>
                        <th width="10%">NOT NULL</th>
                        <th width="10">DEFAULT</th>
                        <th width="15%">BelongsTo</th>
                        <th width="15%">HasMany/HasOne</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($table->columns as $column)
                        <tr id="{{ $column->TABLE_NAME }}.{{ $column->COLUMN_NAME }}">
                            <td>
                                @if($column->primary_key)
                                    <i class="fa-fw fas fa-key"></i>
                                @elseif($column->has_index)
                                    <i class="fa-fw fas fa-tag"></i>
                                @else
                                    <i class="fa-fw fas"></i>
                                @endif
                                {{ $column->COLUMN_NAME }}
                            </td>
                            <td>{{ $column->COLUMN_COMMENT }}</td>
                            <td>{{ $column->COLUMN_TYPE }}</td>
                            <td>
                                @if($column->IS_NULLABLE === 'NO')
                                    YES
                                @endif
                            </td>
                            <td> @if($column->IS_NULLABLE === 'NO' || $column->COLUMN_DEFAULT)
                                    {{ $column->COLUMN_DEFAULT }}
                                @else
                                    <span class="is-null">NULL</span>
                                @endif
                            </td>
                            <td>
                                <a href="#{{ $column->keyColumnUsage->belongs_to }}">
                                    {{ $column->keyColumnUsage->belongs_to }}
                                </a>
                            </td>
                            <td>
                                <ol class="m-0">
                                    @foreach($column->hasManyColumns as $hasMany)
                                        <li>
                                            <a href="#{{ $hasMany->has_many }}">{{ $hasMany->has_many }}</a>
                                        </li>
                                    @endforeach
                                </ol>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @endforeach

</div>

</body>
</html>
