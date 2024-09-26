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
        'from',
        'to',
        'subject',
        'description',
        'employee_names',
        'document_type',
        'file_path',  // Add this field for the file path
    ];
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'document_employee');
    }
}

