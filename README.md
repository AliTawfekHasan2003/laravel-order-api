# Laravel Order API Test

## Description
This project is a simple Laravel API to pay orders and update user points.  
- Changes the order status to **paid**.  
- Updates the user's points based on the order total.  
- Adds a 10-point bonus if the order total is >= 100.  

Demo data is provided via a seeder (`DemoDataSeeder`) with sample users and orders.

---

### API Endpoint

**POST** `/api/orders/{order}/pay`

- Replace `{order}` with the ID of the order you want to pay.  
- No request body is required.

---

### How to Test

You can test the API using **Swagger**, **Postman**, or **Insomnia**:
   
- **Swagger**  
```bash
   - php artisan l5-swagger:generate

```
   - Open in browser:  `http://127.0.0.1:8000/api/documentation#/`

   All possible responses (success, error, etc.) are documented there.

- **Postman / Insomnia**

    `POST http://localhost:8000/api/orders/{order}/pay`
