#!/usr/bin/env bash

npm i
npm run build

while true; do
  tail -f /dev/null & wait ${!}
done
