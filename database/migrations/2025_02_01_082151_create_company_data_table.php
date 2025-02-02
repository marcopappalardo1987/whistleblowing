<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_data', function (Blueprint $table) {
            $table->id();
            $table->string('legal_name'); // The full legal name of the company
            $table->string('vat_number'); // Mandatory number for invoicing
            $table->string('tax_code')->nullable(); // In some cases, especially for public entities or particular types of companies
            $table->string('sdi_code')->nullable(); // Necessary for electronic invoicing in Italy
            $table->string('full_address'); // Street, house number, postal code, city, province, region
            $table->string('country')->nullable(); // In case of foreign companies
            $table->string('rea_number')->nullable(); // Business register, if applicable
            $table->string('registration_number')->nullable(); // If required by the sector of activity
            $table->string('email')->nullable(); // Preferably PEC
            $table->string('phone_number')->nullable(); // For any direct contacts
            $table->string('administrative_contact')->nullable(); // Name and surname of the responsible person
            $table->string('iban')->nullable(); // For payment or reimbursement
            $table->string('bank_name')->nullable(); // Optional, but useful in certain contexts
            $table->text('terms_conditions')->nullable(); // Acceptance of any contractual clauses
            $table->boolean('data_processing_consent')->default(false); // Confirmation of consent in compliance with GDPR
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Related to the id of the user table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_data');
    }
};
