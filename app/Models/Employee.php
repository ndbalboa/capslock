<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'lastName',
        'firstName',
        'middleName',
        'sex',
        'civilStatus',
        'dateOfBirth',
        'religion',
        'emailAddress',
        'phoneNumber',
        'gsisId',
        'pagibigId',
        'philhealthId',
        'sssNo',
        'agencyEmploymentNo',
        'taxId',
        'academicRank',
        'universityPosition',
        'profileImage',
        // Permanent Address
        'permanent_street',
        'permanent_barangay',
        'permanent_city',
        'permanent_province',
        'permanent_country',
        'permanent_zipcode',
        // Residential Address
        'residential_street',
        'residential_barangay',
        'residential_city',
        'residential_province',
        'residential_country',
        'residential_zipcode',
    ];
    public function user()
    {
        return $this->hasOne(User::class);
    }
}


