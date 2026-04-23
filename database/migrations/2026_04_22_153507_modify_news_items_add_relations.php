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
        Schema::table('news_items', function (Blueprint $table) {
            $table->longText('content')->nullable()->after('description');
            $table->string('author')->nullable()->after('content');
            $table->dateTime('published_at')->nullable()->after('author');
            $table->dropColumn('category');
        });

        Schema::create('category_news_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('news_item_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('news_item_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->foreignId('news_item_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_item_tag');
        Schema::dropIfExists('category_news_item');

        Schema::table('news_items', function (Blueprint $table) {
            $table->string('category')->default('standard');
            $table->dropColumn(['content', 'author', 'published_at']);
        });
    }
};
