<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cards', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('uuid')->unique()->index();
            $table->string('name');
            $table->enum('type', \App\Enums\CardTypeEnum::getValues());
            $table->string('color')->default("#ffffff");
            $table->string('contact_button_color')->default("#000000");
            $table->boolean('published')->default(false);
            $table->integer('total_views')->default(0);
            $table->integer('total_saves')->default(0);
            $table->string('qrcode')->nullable();
            $table->string('qrcode_logo')->nullable();
            $table->boolean('pro_mode')->default(false);
            $table->string('slug')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
