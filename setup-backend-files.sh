#!/bin/bash

sed -i "s|{{DB_HOST}}|${DB_HOST}|g" api/register.php
sed -i "s|{{DB_USER}}|${DB_USER}|g" api/register.php
sed -i "s|{{DB_PASS}}|${DB_PASSWORD}|g" api/register.php
sed -i "s|{{DB_NAME}}|${DB_NAME}|g" api/register.php

sed -i "s|{{DB_HOST}}|${DB_HOST}|g" api/login.php
sed -i "s|{{DB_USER}}|${DB_USER}|g" api/login.php
sed -i "s|{{DB_PASS}}|${DB_PASSWORD}|g" api/login.php
sed -i "s|{{DB_NAME}}|${DB_NAME}|g" api/login.php
sed -i "s|{{JWT_SECRET}}|${JWT_SECRET}|g" api/login.php
sed -i "s|{{SITE_URL}}|${SITE_URL}|g" api/login.php

sed -i "s|{{JWT_SECRET}}|${JWT_SECRET}|g" api/refresh-token.php
sed -i "s|{{SITE_URL}}|${SITE_URL}|g" api/refresh-token.php

sed -i "s|{{JWT_SECRET}}|${JWT_SECRET}|g" api/verify-token.php