<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyData extends Model
{
    use HasFactory;

    // The attributes that are mass assignable.
    protected $fillable = [
        'user_id',
        'legal_name',            // The legal name of the company
        'vat_number',            // The VAT number of the company
        'tax_code',              // The tax code of the company
        'sdi_code',              // The SDI code for electronic invoicing
        'full_address',          // The complete address of the company
        'country',               // The country where the company is located
        'rea_number',            // The REA number for company registration
        'registration_number',    // The registration number of the company
        'email',                 // The contact email of the company
        'phone_number',          // The contact phone number of the company
        'administrative_contact', // The name of the administrative contact person
        'iban',                  // The IBAN for bank transactions
        'bank_name',             // The name of the bank where the company holds an account
        'terms_conditions',      // Indicates if the terms and conditions are accepted
        'data_processing_consent', // Indicates if consent for data processing is given
    ];

    protected $casts = [
        'terms_conditions' => 'boolean',
        'data_processing_consent' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a new company data entry.
     *
     * @param array $data The data to create the company entry with.
     * @return \App\Models\CompanyData The created company data instance.
     */
    public static function createCompanyData(array $data)
    {
        return self::create($data); // Create and return the new company data
    }

    /**
     * Retrieve company data by ID.
     *
     * @param int $id The ID of the company data to retrieve.
     * @return \App\Models\CompanyData The company data instance.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no company data is found.
     */
    public static function getCompanyData($id)
    {
        return self::where('user_id', $id)->first(); // Find the company data or fail if not found
    }

    /**
     * Update existing company data by ID.
     *
     * @param int $id The ID of the company data to update.
     * @param array $data The data to update the company entry with.
     * @return \App\Models\CompanyData The updated company data instance.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no company data is found.
     */
    public static function updateCompanyData($id, array $data)
    {
        $companyData = self::where('user_id', $id)->first(); // Find the company data or fail if not found
        $companyData->update($data); // Update the company data with the provided data
        return $companyData; // Return the updated company data
    }

    /**
     * Delete company data by ID.
     *
     * @param int $id The ID of the company data to delete.
     * @return bool True if the deletion was successful, false otherwise.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no company data is found.
     */
    public static function deleteCompanyData($id)
    {
        $companyData = self::where('user_id', $id)->first(); // Find the company data or fail if not found
        return $companyData->delete(); // Delete the company data and return the result
    }

    /**
     * Retrieve all company data entries.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\CompanyData[] A collection of all company data entries.
     */
    public static function getAllCompanyData()
    {
        return self::all(); // Return all company data entries
    }
}
