<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_no',
        'date_issued',
        'from_date',
        'to_date',
        'subject',
        'description',
        'employee_names',
        'document_type',
        'file_path',  
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

