<?php

namespace App\Lib;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Schema;

class Searchable
{
    public function searchable()
    {
        return function ($params, $like = true) {

            $search = request()->search;
            if (!$search) {
                return $this;
            }

            if (!is_array($params)) {
                throw new \Exception("Search parameters should be an array");
            }

            $tableName = $this->from;

            $search = $like ? "%$search%" : $search;

            $this->where(function ($q) use ($params, $search, $tableName) {

                foreach ($params as $param) {
                    $relationData = explode(':', $param);
                    if (@$relationData[1]) {
                        foreach (explode(',', $relationData[1]) as $column) {
                            if (!$relationData[0]) {
                                continue;
                            }
                            $q->orWhereHas($relationData[0], function ($q) use ($column, $search) {
                                $q->where($column, 'like', $search);
                            });
                        }
                    } else {
                        $column = $param;

                        $tableColumns = Schema::getColumnListing($tableName);
                        if (in_array($column, $tableColumns)) {
                            $q->orWhere("$tableName.$column", 'LIKE', $search);
                        } else {
                            $q->orWhereRaw($column . ' LIKE ?', [$search]);
                        }
                    }
                }
            });

            return $this;
        };
    }

    public function filter()
    {
        return function ($params) {

            if (!is_array($params)) {
                throw new \Exception("Search parameters should be an array");
            }

            $tableName = $this->from;

            foreach ($params as $param) {
                $relationData = explode(':', $param);
                $filters = array_keys(request()->all());

                if (@$relationData[1]) {
                    foreach (explode(',', $relationData[1]) as $column) {
                        if (request()->$column != null) {
                            $this->whereHas($relationData[0], function ($q) use ($column, $relationData) {
                                $q->where($column, request()->$column);
                            });
                        }
                    }
                } else {
                    $column = $param;
                    if (in_array($column, $filters) && request()->$column != null) {
                        if (gettype(request()->$column) == 'array') {
                            $this->whereIn("$tableName.$column", request()->$column);
                        } else {
                            $this->where("$tableName.$column", request()->$column);
                        }
                    }
                }
            }
            return $this;
        };
    }

    public function dateFilter()
    {
        return function ($column = 'created_at') {
            if (!request()->date) {
                return $this;
            }
            try {
                $date      = explode('-', request()->date);
                $startDate = Carbon::parse(trim($date[0]))->format('Y-m-d');
                $endDate = @$date[1] ? Carbon::parse(trim(@$date[1]))->format('Y-m-d') : $startDate;
            } catch (\Exception $e) {
                throw ValidationException::withMessages(['error' => 'Invalid date format']);
            }
            $tableName = $this->from;
            return $this->whereDate("$tableName.$column", '>=', $startDate)->whereDate("$tableName.$column", '<=', $endDate);
        };
    }

    public function filterable()
    {
        return function () {
            $request = request();
            $tableName = $this->from;
            $tableColumns = Schema::getColumnListing($tableName);

            if ($request->filter) {

                $filterColumns = array_filter($request->filter, function ($value) {
                    return $value !== null || $value === 0;
                });

                foreach ($filterColumns as $column => $value) {
                    if (in_array($column, $tableColumns)) {
                        $this->where($tableName . '.' . $column, 'LIKE', "%$value%");
                    } else {
                        $this->having($column, 'LIKE', "%$value%");
                    }
                }
            }

            if ($request->date_filter) {
                foreach ($request->date_filter as $column => $dateRange) {
                    if ($dateRange) {
                        try {
                            $date      = explode('-', $dateRange);
                            $startDate = Carbon::parse(trim($date[0]))->format('Y-m-d');
                            $endDate = @$date[1] ? Carbon::parse(trim(@$date[1]))->format('Y-m-d') : $startDate;
                        } catch (\Exception $e) {
                            throw ValidationException::withMessages(['error' => 'Invalid date format']);
                        }

                        if (in_array($column, $tableColumns)) {
                            $this->whereDate("$tableName.$column", '>=', $startDate)->whereDate("$tableName.$column", '<=', $endDate);
                        } else {

                            $this->having("$column", '>=', $startDate)->having("$column", '<=', $endDate);
                        }
                    }
                }
            }

            if ($request->range_filter) {


                foreach ($request->range_filter as $column => $value) {
                    if (in_array($column, $tableColumns)) {
                        if (isset($value['min'])) {
                            $this->where("$tableName.$column", '>=', $value['min']);
                        }
                        if (isset($value['max'])) {
                            $this->where("$tableName.$column", '<=', $value['max']);
                        }
                    } else {

                        if (isset($value['min'])) {
                            $this->having("$column", '>=', $value['min']);
                        }
                        if (isset($value['max'])) {
                            $this->having("$column", '<=', $value['max']);
                        }
                    }
                }
            }

            return $this;
        };
    }

    public function orderable()
    {
        return function () {
            $request = request();
            $orderByColumn = $request->order_by_column ?? 'id';
            $orderBy = $request->order_by ?? 'desc';
            return $this->orderBy($orderByColumn, $orderBy);
        };
    }

    public function dynamicPaginate()
    {
        return function () {
            return  $this->paginate(request()->per_page ?? getPaginate());
        };
    }
}
