<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_prices', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('key')->unique();
            $table->string('service_key', 80);
            $table->string('variant_key', 80)->nullable();
            $table->string('tier_key', 80)->nullable();
            $table->string('label', 120);
            $table->string('unit_label', 40)->nullable();
            $table->string('currency', 3)->default('NGN');
            $table->string('pricing_type', 20);
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('max_amount')->nullable();
            $table->unsignedInteger('distance_max_km')->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();

            $table->index(['service_key', 'status']);
            $table->index(['pricing_type', 'status']);
        });

        $now = now();

        DB::table('service_prices')->insert(array_map(
            static fn (array $price): array => $price + [
                'id' => (string) Str::uuid7(),
                'currency' => 'NGN',
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                ['key' => 'services.boarding.variants.dogs.tiers.basic', 'service_key' => 'boarding', 'variant_key' => 'dogs', 'tier_key' => 'basic', 'label' => 'Basic', 'unit_label' => '/night', 'pricing_type' => 'fixed', 'amount' => 10000, 'max_amount' => null, 'distance_max_km' => null],
                ['key' => 'services.boarding.variants.dogs.tiers.premium', 'service_key' => 'boarding', 'variant_key' => 'dogs', 'tier_key' => 'premium', 'label' => 'Premium', 'unit_label' => '/night', 'pricing_type' => 'fixed', 'amount' => 18000, 'max_amount' => null, 'distance_max_km' => null],
                ['key' => 'services.boarding.variants.dogs.tiers.deluxe', 'service_key' => 'boarding', 'variant_key' => 'dogs', 'tier_key' => 'deluxe', 'label' => 'Deluxe', 'unit_label' => '/night', 'pricing_type' => 'fixed', 'amount' => 25000, 'max_amount' => null, 'distance_max_km' => null],
                ['key' => 'services.boarding.variants.cats.tiers.cozy', 'service_key' => 'boarding', 'variant_key' => 'cats', 'tier_key' => 'cozy', 'label' => 'Cozy', 'unit_label' => '/night', 'pricing_type' => 'fixed', 'amount' => 8000, 'max_amount' => null, 'distance_max_km' => null],
                ['key' => 'services.boarding.variants.cats.tiers.premium', 'service_key' => 'boarding', 'variant_key' => 'cats', 'tier_key' => 'premium', 'label' => 'Premium', 'unit_label' => '/night', 'pricing_type' => 'fixed', 'amount' => 14000, 'max_amount' => null, 'distance_max_km' => null],
                ['key' => 'services.boarding.variants.cats.tiers.deluxe', 'service_key' => 'boarding', 'variant_key' => 'cats', 'tier_key' => 'deluxe', 'label' => 'Deluxe', 'unit_label' => '/night', 'pricing_type' => 'fixed', 'amount' => 22000, 'max_amount' => null, 'distance_max_km' => null],
                ['key' => 'services.boarding.variants.exotic.tiers.small', 'service_key' => 'boarding', 'variant_key' => 'exotic', 'tier_key' => 'small', 'label' => 'Small Mammal', 'unit_label' => '/night', 'pricing_type' => 'estimate', 'amount' => 6000, 'max_amount' => 8000, 'distance_max_km' => null],
                ['key' => 'services.boarding.variants.exotic.tiers.specialist', 'service_key' => 'boarding', 'variant_key' => 'exotic', 'tier_key' => 'specialist', 'label' => 'Specialist', 'unit_label' => '/night', 'pricing_type' => 'estimate', 'amount' => 12000, 'max_amount' => 15000, 'distance_max_km' => null],
                ['key' => 'services.grooming.tiers.bath', 'service_key' => 'grooming', 'variant_key' => null, 'tier_key' => 'bath', 'label' => 'Bath & Brush', 'unit_label' => '/session', 'pricing_type' => 'fixed', 'amount' => 10000, 'max_amount' => null, 'distance_max_km' => null],
                ['key' => 'services.grooming.tiers.full', 'service_key' => 'grooming', 'variant_key' => null, 'tier_key' => 'full', 'label' => 'Full Groom', 'unit_label' => '/session', 'pricing_type' => 'fixed', 'amount' => 18000, 'max_amount' => null, 'distance_max_km' => null],
                ['key' => 'services.grooming.tiers.spa', 'service_key' => 'grooming', 'variant_key' => null, 'tier_key' => 'spa', 'label' => 'Luxury Spa', 'unit_label' => '/session', 'pricing_type' => 'fixed', 'amount' => 28000, 'max_amount' => null, 'distance_max_km' => null],
                ['key' => 'services.vet-care.tiers.consultation', 'service_key' => 'vet-care', 'variant_key' => null, 'tier_key' => 'consultation', 'label' => 'Wellness Consultation', 'unit_label' => '/visit', 'pricing_type' => 'fixed', 'amount' => 12000, 'max_amount' => null, 'distance_max_km' => null],
                ['key' => 'services.vet-care.tiers.vaccination', 'service_key' => 'vet-care', 'variant_key' => null, 'tier_key' => 'vaccination', 'label' => 'Vaccination Visit', 'unit_label' => '/visit', 'pricing_type' => 'fixed', 'amount' => 15000, 'max_amount' => null, 'distance_max_km' => null],
                ['key' => 'services.vet-care.tiers.comprehensive-exam', 'service_key' => 'vet-care', 'variant_key' => null, 'tier_key' => 'comprehensive-exam', 'label' => 'Comprehensive Exam', 'unit_label' => '/visit', 'pricing_type' => 'fixed', 'amount' => 18000, 'max_amount' => null, 'distance_max_km' => null],
                ['key' => 'services.training.tiers.puppy', 'service_key' => 'training', 'variant_key' => null, 'tier_key' => 'puppy', 'label' => 'Puppy Foundation', 'unit_label' => '/programme', 'pricing_type' => 'estimate', 'amount' => 80000, 'max_amount' => 120000, 'distance_max_km' => null],
                ['key' => 'services.training.tiers.obedience', 'service_key' => 'training', 'variant_key' => null, 'tier_key' => 'obedience', 'label' => 'Basic Obedience', 'unit_label' => '/programme', 'pricing_type' => 'estimate', 'amount' => 100000, 'max_amount' => 150000, 'distance_max_km' => null],
                ['key' => 'cost_calculator.rates.boarding.small', 'service_key' => 'boarding', 'variant_key' => 'cost-calculator', 'tier_key' => 'small', 'label' => 'Boarding · Small pet', 'unit_label' => '/night', 'pricing_type' => 'estimate', 'amount' => 8000, 'max_amount' => 12000, 'distance_max_km' => null],
                ['key' => 'cost_calculator.rates.boarding.medium', 'service_key' => 'boarding', 'variant_key' => 'cost-calculator', 'tier_key' => 'medium', 'label' => 'Boarding · Medium pet', 'unit_label' => '/night', 'pricing_type' => 'estimate', 'amount' => 12000, 'max_amount' => 18000, 'distance_max_km' => null],
                ['key' => 'cost_calculator.rates.boarding.large', 'service_key' => 'boarding', 'variant_key' => 'cost-calculator', 'tier_key' => 'large', 'label' => 'Boarding · Large pet', 'unit_label' => '/night', 'pricing_type' => 'estimate', 'amount' => 18000, 'max_amount' => 28000, 'distance_max_km' => null],
                ['key' => 'cost_calculator.rates.grooming.small', 'service_key' => 'grooming', 'variant_key' => 'cost-calculator', 'tier_key' => 'small', 'label' => 'Grooming · Small pet', 'unit_label' => '/session', 'pricing_type' => 'estimate', 'amount' => 5000, 'max_amount' => 8000, 'distance_max_km' => null],
                ['key' => 'cost_calculator.rates.grooming.medium', 'service_key' => 'grooming', 'variant_key' => 'cost-calculator', 'tier_key' => 'medium', 'label' => 'Grooming · Medium pet', 'unit_label' => '/session', 'pricing_type' => 'estimate', 'amount' => 8000, 'max_amount' => 15000, 'distance_max_km' => null],
                ['key' => 'cost_calculator.rates.grooming.large', 'service_key' => 'grooming', 'variant_key' => 'cost-calculator', 'tier_key' => 'large', 'label' => 'Grooming · Large pet', 'unit_label' => '/session', 'pricing_type' => 'estimate', 'amount' => 15000, 'max_amount' => 25000, 'distance_max_km' => null],
                ['key' => 'cost_calculator.rates.vet.small', 'service_key' => 'vet-care', 'variant_key' => 'cost-calculator', 'tier_key' => 'small', 'label' => 'Vet care · Small pet', 'unit_label' => '/visit', 'pricing_type' => 'estimate', 'amount' => 3000, 'max_amount' => 8000, 'distance_max_km' => null],
                ['key' => 'cost_calculator.rates.vet.medium', 'service_key' => 'vet-care', 'variant_key' => 'cost-calculator', 'tier_key' => 'medium', 'label' => 'Vet care · Medium pet', 'unit_label' => '/visit', 'pricing_type' => 'estimate', 'amount' => 5000, 'max_amount' => 12000, 'distance_max_km' => null],
                ['key' => 'cost_calculator.rates.vet.large', 'service_key' => 'vet-care', 'variant_key' => 'cost-calculator', 'tier_key' => 'large', 'label' => 'Vet care · Large pet', 'unit_label' => '/visit', 'pricing_type' => 'estimate', 'amount' => 8000, 'max_amount' => 20000, 'distance_max_km' => null],
                ['key' => 'cost_calculator.rates.training.small', 'service_key' => 'training', 'variant_key' => 'cost-calculator', 'tier_key' => 'small', 'label' => 'Training · Small pet', 'unit_label' => '/session', 'pricing_type' => 'estimate', 'amount' => 10000, 'max_amount' => 15000, 'distance_max_km' => null],
                ['key' => 'cost_calculator.rates.training.medium', 'service_key' => 'training', 'variant_key' => 'cost-calculator', 'tier_key' => 'medium', 'label' => 'Training · Medium pet', 'unit_label' => '/session', 'pricing_type' => 'estimate', 'amount' => 15000, 'max_amount' => 25000, 'distance_max_km' => null],
                ['key' => 'cost_calculator.rates.training.large', 'service_key' => 'training', 'variant_key' => 'cost-calculator', 'tier_key' => 'large', 'label' => 'Training · Large pet', 'unit_label' => '/session', 'pricing_type' => 'estimate', 'amount' => 20000, 'max_amount' => 35000, 'distance_max_km' => null],
                ['key' => 'cost_calculator.fallback_rate', 'service_key' => 'general', 'variant_key' => 'cost-calculator', 'tier_key' => 'fallback', 'label' => 'Fallback estimate range', 'unit_label' => null, 'pricing_type' => 'estimate', 'amount' => 5000, 'max_amount' => 10000, 'distance_max_km' => null],
                ['key' => 'transport.products.transport-city-transfer.pricing.rates.0', 'service_key' => 'local-transport', 'variant_key' => 'transport-city-transfer', 'tier_key' => 'distance-10', 'label' => 'City Pet Transfer · up to 10 km', 'unit_label' => null, 'pricing_type' => 'distance', 'amount' => 10000, 'max_amount' => null, 'distance_max_km' => 10],
                ['key' => 'transport.products.transport-city-transfer.pricing.rates.1', 'service_key' => 'local-transport', 'variant_key' => 'transport-city-transfer', 'tier_key' => 'distance-25', 'label' => 'City Pet Transfer · up to 25 km', 'unit_label' => null, 'pricing_type' => 'distance', 'amount' => 15000, 'max_amount' => null, 'distance_max_km' => 25],
                ['key' => 'transport.products.transport-city-transfer.pricing.rates.2', 'service_key' => 'local-transport', 'variant_key' => 'transport-city-transfer', 'tier_key' => 'distance-40', 'label' => 'City Pet Transfer · up to 40 km', 'unit_label' => null, 'pricing_type' => 'distance', 'amount' => 20000, 'max_amount' => null, 'distance_max_km' => 40],
                ['key' => 'transport.products.transport-vet-transfer.pricing.rates.0', 'service_key' => 'local-transport', 'variant_key' => 'transport-vet-transfer', 'tier_key' => 'distance-10', 'label' => 'Vet Transfer · up to 10 km', 'unit_label' => null, 'pricing_type' => 'distance', 'amount' => 12500, 'max_amount' => null, 'distance_max_km' => 10],
                ['key' => 'transport.products.transport-vet-transfer.pricing.rates.1', 'service_key' => 'local-transport', 'variant_key' => 'transport-vet-transfer', 'tier_key' => 'distance-25', 'label' => 'Vet Transfer · up to 25 km', 'unit_label' => null, 'pricing_type' => 'distance', 'amount' => 17500, 'max_amount' => null, 'distance_max_km' => 25],
                ['key' => 'transport.products.transport-vet-transfer.pricing.rates.2', 'service_key' => 'local-transport', 'variant_key' => 'transport-vet-transfer', 'tier_key' => 'distance-40', 'label' => 'Vet Transfer · up to 40 km', 'unit_label' => null, 'pricing_type' => 'distance', 'amount' => 22500, 'max_amount' => null, 'distance_max_km' => 40],
                ['key' => 'transport.products.transport-airport-transfer.pricing.amount', 'service_key' => 'local-transport', 'variant_key' => 'transport-airport-transfer', 'tier_key' => 'airport', 'label' => 'Airport Transfer', 'unit_label' => '/trip', 'pricing_type' => 'fixed', 'amount' => 25000, 'max_amount' => null, 'distance_max_km' => null],
                ['key' => 'transport.rules.waiting_increment_amount', 'service_key' => 'local-transport', 'variant_key' => 'transport-rules', 'tier_key' => 'waiting', 'label' => 'Waiting time increment', 'unit_label' => 'per 30 minutes', 'pricing_type' => 'surcharge', 'amount' => 2500, 'max_amount' => null, 'distance_max_km' => null],
                ['key' => 'transport.rules.additional_stop_amount', 'service_key' => 'local-transport', 'variant_key' => 'transport-rules', 'tier_key' => 'additional-stop', 'label' => 'Additional stop charge', 'unit_label' => 'per stop', 'pricing_type' => 'surcharge', 'amount' => 3000, 'max_amount' => null, 'distance_max_km' => null],
            ],
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_prices');
    }
};
