<p align="center"><a href="#" target="_blank"><img src="https://drive.google.com/file/d/17yOvlVOUbbm7I3tBi88_jloURxdrkv4y/view?usp=drive_link" width="400" alt="Kitchen Track Pro Logo"></a></p>

<p align="center">
<a href="https://github.com/your-repo/kitchen-track-pro/actions"><img src="https://github.com/your-repo/kitchen-track-pro/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/your-package/kitchen-track-pro"><img src="https://img.shields.io/packagist/dt/your-package/kitchen-track-pro" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/your-package/kitchen-track-pro"><img src="https://img.shields.io/packagist/v/your-package/kitchen-track-pro" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/your-package/kitchen-track-pro"><img src="https://img.shields.io/packagist/l/your-package/kitchen-track-pro" alt="License"></a>
</p>

## About Kitchen Track Pro

Kitchen Track Pro is a comprehensive kitchen management application built using Laravel 11 and Filament PHP 3.2. It is designed to streamline kitchen operations, manage inventory, track orders, and optimize workflow for kitchens of all sizes. This application leverages the power of Laravel's robust framework and Filament's intuitive PHP components to deliver an efficient and user-friendly experience.

### Features

-   **Order Management**: Track and manage orders in real-time.
-   **Inventory Management**: Maintain and monitor stock levels, and receive alerts for low inventory.
-   **Recipe Management**: Store and organize recipes with detailed instructions and ingredient lists.
-   **User Management**: Manage roles and permissions for staff members.
-   **Reporting**: Generate comprehensive reports on kitchen operations, inventory usage, and order history.
-   **Notifications**: Receive notifications for important events such as new orders, low stock levels, and more.

### Modules

1. **Inventory**

    - Enter inventory items in bulk at once.
    - Keep track of stock levels, update quantities, and manage supplies efficiently.

2. **Items**

    - Add individual items to the inventory.
    - Maintain details of each item, such as quantity, supplier, and cost.

3. **Dish**

    - Combine multiple items with specific quantities to create a dish.
    - Manage recipes and ensure consistency in dish preparation.

4. **Sale**
    - Record the number of dishes sold along with their respective quantities.
    - Automatically deduct the inventory quantities based on the items used in sold dishes.

### Inventory Management

When a dish is sold, Kitchen Track Pro automatically adjusts the inventory levels. The specific quantities of each item used in the dish are deducted from the stored inventory, ensuring accurate stock levels are maintained.

## Installation

### Prerequisites

Before you begin, ensure you have met the following requirements:

-   PHP >= 8.0
-   Composer
-   Node.js & NPM
-   MySQL or any other supported database

### Steps

1. **Clone the Repository**

    ```bash
    git clone https://github.com/your-repo/kitchen-track-pro.git
    cd kitchen-track-pro
    ```

2. **Install Dependencies**

    ```bash
    composer install
    npm install
    npm run dev
    ```

3. **Environment Configuration**

    - Copy the `.env.example` file to `.env`:
        ```bash
        cp .env.example .env
        ```
    - Update the `.env` file with your database and other configuration details.

4. **Generate Application Key**

    ```bash
    php artisan key:generate
    ```

5. **Run Migrations**

    ```bash
    php artisan migrate
    ```

6. **Seed the Database (Optional)**

    ```bash
    php artisan db:seed
    ```

7. **Serve the Application**

    ```bash
    php artisan serve
    ```

    You can now access the application at `http://localhost:8000`.

## Contributing

Contributions are welcome! Please follow these steps to contribute:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes and commit them (`git commit -m 'Add some feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Create a pull request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact

For any questions or feedback, please contact us at [support@kitchentrackpro.com](mailto:support@kitchentrackpro.com).

---

Thank you for using Kitchen Track Pro! We hope it makes your kitchen management tasks easier and more efficient.
