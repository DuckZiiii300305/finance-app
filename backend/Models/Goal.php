<?php

class Goal
{
    public $id;
    public $user_id;
    public $name;
    public $target_amount;
    public $current_amount;
    public $start_date;
    public $end_date;
    public $auto_daily_amount;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($data)
    {
        $this->id = $data['id'] ?? null;
        $this->user_id = $data['user_id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->target_amount = $data['target_amount'] ?? 0;
        $this->current_amount = $data['current_amount'] ?? 0;
        $this->start_date = $data['start_date'] ?? null;
        $this->end_date = $data['end_date'] ?? null;
        $this->auto_daily_amount = $data['auto_daily_amount'] ?? 0;
        $this->status = $data['status'] ?? 'active';
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }
}