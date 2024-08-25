#!/bin/bash
# error handling
set -e

# Navigate to your app directory
cd /var/www/html/resrequest

# Install dependencies
npm run build

# Restart PM2 process
pm2 restart resrequest

echo "Services restarted successfully."
