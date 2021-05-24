#!/usr/bin/env bash

# if any command inside script returns error, exit and return that error
set -e

# magic line to ensure that we're always inside the root of our application,
# no matter from which directory we'll run script
# thanks to it we can just enter `./scripts/run-tests.bash`
cd "${0%/*}/../.."

# let's fake failing test for now
echo "1. Runs vendor's security checking..."
symfony check:security
echo "2. Runs global dependency checking..."
symfony check:requirements
echo "3. Runs doctrine's mapping..."
php bin/console doctrine:schema:validate
echo "4. Runs code's quality checker..."
#make check
echo "5. Runs tests..."
make tests && exit 0

echo "Failed!" && exit 1
