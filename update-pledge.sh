#!/bin/bash

# PMA Pledge Update Script
# Usage: ./update-pledge.sh <amount> [month]
# Example: ./update-pledge.sh 7500 October

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if amount is provided
if [ -z "$1" ]; then
    echo -e "${RED}Error: Amount is required${NC}"
    echo "Usage: ./update-pledge.sh <amount> [month]"
    echo "Example: ./update-pledge.sh 7500 October"
    exit 1
fi

AMOUNT=$1
MONTH=$2

# Get API token from environment or prompt
if [ -z "$PMA_API_TOKEN" ]; then
    echo -e "${YELLOW}No PMA_API_TOKEN found in environment${NC}"
    echo -e "${YELLOW}You need to generate an API token first.${NC}"
    echo ""
    echo "To generate a token, run this in tinker:"
    echo "php artisan tinker"
    echo ""
    echo "Then execute:"
    echo "\$user = User::first();"
    echo "\$token = \$user->createToken('pledge-updater')->plainTextToken;"
    echo "echo \$token;"
    echo ""
    echo "Then set it as an environment variable:"
    echo "export PMA_API_TOKEN='your-token-here'"
    exit 1
fi

# Build the API URL
API_URL="http://pma.test/api/pledge/update"

# Build JSON payload
if [ -z "$MONTH" ]; then
    JSON_DATA="{\"current_amount\": $AMOUNT}"
else
    JSON_DATA="{\"current_amount\": $AMOUNT, \"month\": \"$MONTH\"}"
fi

# Make the API call
echo -e "${YELLOW}Updating pledge progress...${NC}"
echo "Amount: R$AMOUNT"
if [ ! -z "$MONTH" ]; then
    echo "Month: $MONTH"
fi
echo ""

RESPONSE=$(curl -s -X POST "$API_URL" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer $PMA_API_TOKEN" \
    -d "$JSON_DATA")

# Check if request was successful
if echo "$RESPONSE" | grep -q '"success":true'; then
    echo -e "${GREEN}✓ Pledge updated successfully!${NC}"
    echo ""

    # Extract and display data using grep and sed
    CURRENT=$(echo "$RESPONSE" | grep -o '"current_amount":"[^"]*"' | cut -d'"' -f4)
    PLEDGE_MONTH=$(echo "$RESPONSE" | grep -o '"month":"[^"]*"' | cut -d'"' -f4)
    GOAL=$(echo "$RESPONSE" | grep -o '"goal_amount":"[^"]*"' | cut -d'"' -f4)
    PERCENTAGE=$(echo "$RESPONSE" | grep -o '"percentage":[^,}]*' | cut -d':' -f2)

    echo "Current Amount: R$CURRENT"
    echo "Month: $PLEDGE_MONTH"
    echo "Goal: R$GOAL"
    echo "Progress: $PERCENTAGE%"
else
    echo -e "${RED}✗ Failed to update pledge${NC}"
    echo ""
    echo "Response:"
    echo "$RESPONSE"
    exit 1
fi
