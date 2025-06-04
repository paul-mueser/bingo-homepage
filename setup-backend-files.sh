#!/bin/bash

sed -i "s|{{DB_HOST}}|${DB_HOST}|g" api/register.php
sed -i "s|{{DB_USER}}|${DB_USER}|g" api/register.php
sed -i "s|{{DB_PASS}}|${DB_PASSWORD}|g" api/register.php
sed -i "s|{{DB_NAME}}|${DB_NAME}|g" api/register.php
sed -i "s|{{CORRECT_AUTH_CODE}}|${CORRECT_AUTH_CODE}|g" api/register.php

sed -i "s|{{DB_HOST}}|${DB_HOST}|g" api/login.php
sed -i "s|{{DB_USER}}|${DB_USER}|g" api/login.php
sed -i "s|{{DB_PASS}}|${DB_PASSWORD}|g" api/login.php
sed -i "s|{{DB_NAME}}|${DB_NAME}|g" api/login.php
sed -i "s|{{JWT_SECRET}}|${JWT_SECRET}|g" api/login.php
sed -i "s|{{SITE_URL}}|${SITE_URL}|g" api/login.php

sed -i "s|{{JWT_SECRET}}|${JWT_SECRET}|g" api/refresh-token.php
sed -i "s|{{SITE_URL}}|${SITE_URL}|g" api/refresh-token.php

sed -i "s|{{JWT_SECRET}}|${JWT_SECRET}|g" api/verify-token.php

sed -i "s|{{DB_HOST}}|${DB_HOST}|g" api/fetch-bingo-board.php
sed -i "s|{{DB_USER}}|${DB_USER}|g" api/fetch-bingo-board.php
sed -i "s|{{DB_PASS}}|${DB_PASSWORD}|g" api/fetch-bingo-board.php
sed -i "s|{{DB_NAME}}|${DB_NAME}|g" api/fetch-bingo-board.php
sed -i "s|{{JWT_SECRET}}|${JWT_SECRET}|g" api/fetch-bingo-board.php

sed -i "s|{{DB_HOST}}|${DB_HOST}|g" api/fetch-bingo-events.php
sed -i "s|{{DB_USER}}|${DB_USER}|g" api/fetch-bingo-events.php
sed -i "s|{{DB_PASS}}|${DB_PASSWORD}|g" api/fetch-bingo-events.php
sed -i "s|{{DB_NAME}}|${DB_NAME}|g" api/fetch-bingo-events.php
sed -i "s|{{JWT_SECRET}}|${JWT_SECRET}|g" api/fetch-bingo-events.php

sed -i "s|{{DB_HOST}}|${DB_HOST}|g" api/update-bingo-event.php
sed -i "s|{{DB_USER}}|${DB_USER}|g" api/update-bingo-event.php
sed -i "s|{{DB_PASS}}|${DB_PASSWORD}|g" api/update-bingo-event.php
sed -i "s|{{DB_NAME}}|${DB_NAME}|g" api/update-bingo-event.php
sed -i "s|{{JWT_SECRET}}|${JWT_SECRET}|g" api/update-bingo-event.php