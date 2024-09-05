# Users - Empleados (Ready)
- name
    -   Max_Length: 30
    -   Solo texto sin caracteres especiales
- phone
    -   Length: 11
    -   Unique
    -   Numbers
- email
    -   Unique
    -   Email
- password
    -   Password
    -   Hashed
    -   Min_Length: 8
    -   Min_Numbers: 1
    -   Min_Special_Chars: 1
    -   Min_Letters: 1

# Currencies (Ready)
- value
    -   Required, previo a cualquier venta
    -   Negative: No

# Clients (Ready)
- name
    -   MaxLength: 30
    -   Solo texto sin caracteres especiales
- last_name
    -   MaxLength: 30
    -   Solo texto sin caracteres especiales
- document
    -   Unique
    -   MaxLength: 10
- phone
    -   Length: 11
    -   Unique
    -   Numbers
- address
    -   MaxLength: 100
    -   Solo texto sin caracteres especiales

# Categories (Ready)
- name
    -   Max_Length: 30
    -   Solo texto sin caracteres especiales
    -   Unique

# Providers (Ready)
- name
    -   Max_Length: 30
    -   Solo texto sin caracteres especiales
- address
    -   MaxLength: 100
    -   Solo texto sin caracteres especiales
- phone
    -   Length: 11
    -   Unique
    -   Numbers
- rif
    -   Unique
    -   Length: 10
- type
    -   J, G, V, E

# Products (Ready)
- name
    -   Max_Length: 120
- barcode
    -   Unique
    -   Max_Length: 20
- cost
    -   Negative: No
- price
    -   Negative: No
- stock
    -   Value: > 0
    -   Negative: No
- min_stock
    -   Min_Value: 1
    -   Negative: No
- image
    -   Size: < 1MB
    -   Format: JPG, JPEG, PNG
- category_id
- provider_id

# Sales (Ready)
- total
    -   Negative: No
    -   Min_Value: 1
- cash
    -   Negative: No
    -   Optional
- bs
    -   Negative: No
    -   Optional

    -   Required: Cash o BS
- change
    -   Optional
    -   Negative: No
- status
- client_id
- user_id
- currency_id

# Sales_Details (auto)
- price
    -   Negative: No
    -   Min_Value: 1
- quantity
    -   Negative: No
    -   Min_Value: 1
- product_id
- sale_id