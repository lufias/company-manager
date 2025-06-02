#!/bin/bash

echo "🚀 Setting up Laravel application..."

# Run migrations
echo "📊 Running database migrations..."
docker compose exec app php artisan migrate:fresh --force

# Create storage link
echo "🔗 Creating storage symbolic link..."
if [ -L "public/storage" ]; then
    echo "   ℹ️  Storage link already exists, skipping..."
else
    docker compose exec app php artisan storage:link
fi

# Seed database (optional)
echo "🌱 Seeding database..."
docker compose exec app php artisan db:seed --force

echo "✅ Setup complete! Your application is ready."
echo "🌐 Visit: http://localhost:8000" 