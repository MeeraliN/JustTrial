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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('managed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title', 191);
            $table->text('description');
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->enum('property_type', ['house', 'flat', 'room', 'pg', 'hostel', 'shop', 'office']);
            $table->decimal('rent_amount', 12, 2);
            $table->decimal('deposit_amount', 12, 2)->nullable();
            $table->decimal('maintenance_amount', 12, 2)->nullable();
            $table->string('currency_code', 10)->default('INR');
            $table->unsignedTinyInteger('bhk')->nullable();
            $table->unsignedTinyInteger('bedrooms')->nullable();
            $table->unsignedTinyInteger('bathrooms')->nullable();
            $table->decimal('size_sqft', 10, 2)->nullable();
            $table->enum('furnishing', ['unfurnished', 'semi_furnished', 'fully_furnished'])->nullable();
            $table->smallInteger('floor_number')->nullable();
            $table->smallInteger('total_floors')->nullable();
            $table->unsignedSmallInteger('parking_spots')->nullable();
            $table->enum('preferred_tenant', ['any', 'family', 'bachelor_male', 'bachelor_female', 'students', 'working_professionals'])->default('any');
            $table->date('available_from')->nullable();
            $table->string('address_line1', 191);
            $table->string('address_line2', 191)->nullable();
            $table->foreignId('city_id')->constrained('cities')->restrictOnDelete();
            $table->foreignId('locality_id')->nullable()->constrained('localities')->nullOnDelete();
            $table->string('pincode', 12)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('location_precision', ['exact', 'approximate'])->default('approximate');
            $table->text('house_rules')->nullable();
            $table->boolean('is_owner_verified')->default(false);
            $table->boolean('is_agent_listing')->default(false);
            $table->boolean('is_sponsored')->default(false);
            $table->enum('status', ['draft', 'pending', 'active', 'rejected', 'paused', 'rented', 'expired'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_status_changed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'city_id']);
            $table->index(['property_type', 'rent_amount']);
            $table->index(['owner_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
