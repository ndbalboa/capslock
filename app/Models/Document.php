<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_no', 
        'series_no',
        'date_issued', 
        'from_date', 
        'to_date', 
        'subject', 
        'description', 
        'document_type', 
        'file_path', 
        'employee_names' 
    ];

    protected $casts = [
        'employee_names' => 'array',
    ];

    public function documentEmployeeNames()
    {
        return $this->hasMany(DocumentEmployeeName::class);
    }
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'document_employee', 'document_id', 'employee_id');
    }
}

