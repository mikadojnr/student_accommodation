# SecureStay - Student Accommodation Platform

A secure, fraud-prevention focused platform for student accommodation listings with advanced verification systems.

## 🏗️ System Architecture

### Overview
SecureStay is built using a custom PHP MVC framework with a focus on security, verification, and fraud prevention. The system implements multiple layers of user and property verification to ensure trust and safety.

### Architecture Components

```
┌─────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                       │
├─────────────────────────────────────────────────────────────┤
│  • Responsive Web Interface (Tailwind CSS)                  │
│  • RESTful API Endpoints                                    │
│  • Real-time Chat Interface                                 │
│  • Mobile-First Design                                      │
└─────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────┐
│                    APPLICATION LAYER                        │
├─────────────────────────────────────────────────────────────┤
│  • MVC Controllers (Auth, Property, Verification)           │
│  • Business Logic Services                                  │
│  • Verification Services (Jumio/Onfido Integration)         │
│  • Security Middleware                                      │
└─────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────┐
│                      CORE LAYER                             │
├─────────────────────────────────────────────────────────────┤
│  • Custom Router with Dynamic Routes                        │
│  • Database Abstraction Layer                               │
│  • View Engine with Layout Support                          │
│  • Migration System                                         │
└─────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────┐
│                     DATA LAYER                              │
├─────────────────────────────────────────────────────────────┤
│  • MySQL Database                                           │
│  • File Storage (Images, Documents)                         │
│  • Session Management                                       │
│  • Activity Logging                                         │
└─────────────────────────────────────────────────────────────┘
```

### Security Features

1. **Multi-Layer Verification System**
   - Government ID verification (Jumio integration)
   - Biometric face matching (Onfido integration)
   - Student status verification
   - Address verification

2. **Fraud Prevention**
   - Real-time scam detection in messages
   - Suspicious activity monitoring
   - Secure payment escrow system
   - Property authenticity verification

3. **Data Protection**
   - End-to-end encrypted messaging
   - Secure file uploads with validation
   - SQL injection prevention
   - XSS protection
   - CSRF token validation

## 🛠️ Technology Stack

- **Backend**: PHP 8.0+ (Custom MVC Framework)
- **Database**: MySQL 8.0+
- **Frontend**: HTML5, Tailwind CSS, Vanilla JavaScript
- **File Storage**: Local filesystem with planned cloud integration
- **Verification**: Jumio, Onfido APIs
- **Security**: bcrypt password hashing, session management

## 📋 Prerequisites

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache/Nginx web server
- Composer (for dependency management)
- Node.js (for frontend build tools, optional)

## 🚀 Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/repo_name.git
cd securestay
```

### 2. Environment Configuration

Create a `.env` file in the root directory:

```env
# Application Settings
APP_NAME=SecureStay
APP_ENV=development
APP_URL=http://localhost:8000

# Database Configuration
DB_HOST=localhost
DB_DATABASE=student_accommodation
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Verification Services (Optional for development)
JUMIO_API_KEY=your_jumio_api_key
JUMIO_API_SECRET=your_jumio_api_secret
ONFIDO_API_KEY=your_onfido_api_key

# Mail Configuration (Optional)
MAIL_FROM=noreply@securestay.com
```

### 3. Database Setup

#### Create Database
```sql
CREATE DATABASE student_accommodation;
```

#### Run the SQL Schema
```bash
mysql -u your_username -p student_accommodation < create_tables.sql
```

#### Insert Sample Data (Optional)
```bash
mysql -u your_username -p student_accommodation < sample_data.sql
```

### 4. Run Database Migrations

```bash
php database/migrate.php
```

To rollback the last migration batch:
```bash
php database/migrate.php rollback
```

### 5. Set Directory Permissions

```bash
chmod -R 755 public/uploads/
chmod -R 755 logs/
```

### 6. Start the Development Server

#### Using PHP Built-in Server
```bash
php -S localhost:8000 -t public
```

#### Using Apache/Nginx
Configure your web server to point to the `public` directory as the document root.

**Apache Virtual Host Example:**
```apache
<VirtualHost *:80>
    ServerName securestay.local
    DocumentRoot /path/to/securestay/public
    
    <Directory /path/to/securestay/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 7. Access the Application

Open your browser and navigate to:
- **Development Server**: http://localhost:8000
- **Apache/Nginx**: http://securestay.local (or your configured domain)

## 🗄️ Database Migrations

### Creating a New Migration

1. Create a new migration file in `database/migrations/`:
```php
<?php

namespace DatabaseMigrations;

use AppCoreMigration;

class YourMigrationName extends Migration
{
    public function up()
    {
        $this->execute("
            CREATE TABLE example (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function down()
    {
        $this->execute("DROP TABLE IF EXISTS example");
    }
}
```

2. Run the migration:
```bash
php database/migrate.php
```

### Migration Commands

- **Run all pending migrations**: `php database/migrate.php`
- **Rollback last batch**: `php database/migrate.php rollback`
- **Check migration status**: The system automatically tracks which migrations have been run

## 👥 User Accounts & Testing

### Default Test Accounts

After running the sample data, you can use these accounts:

**Landlord Account:**
- Email: john.smith@landlord.com
- Password: password

**Student Account:**
- Email: sarah.johnson@student.ox.ac.uk
- Password: password

### Creating New Accounts

1. Navigate to `/register`
2. Choose account type (Student or Landlord)
3. Complete the registration form
4. Verify your account through the verification process

## 🔧 Development Workflow

### Software Development Methodology

This project follows **Agile Development** principles with:

1. **Iterative Development**: Features are developed in small, manageable iterations
2. **Test-Driven Approach**: Core functionality is tested before deployment
3. **Security-First Design**: Every feature considers security implications
4. **User-Centered Design**: UI/UX decisions based on student and landlord needs

### Code Structure

```
securestay/
├── App/
│   ├── Controllers/          # MVC Controllers
│   ├── Core/                # Framework core classes
│   ├── Models/              # Data models
│   └── Services/            # Business logic services
├── bootstrap/
│   └── app.php              # Application bootstrap
├── database/
│   ├── migrations/          # Database migrations
│   └── migrate.php          # Migration runner
├── public/                  # Web-accessible files
│   ├── uploads/            # User uploaded files
│   └── index.php           # Application entry point
├── resources/
│   └── views/              # View templates
├── .env                    # Environment configuration
└── README.md              # This file
```

### Adding New Features

1. **Create Controller**: Add new controller in `App/Controllers/`
2. **Define Routes**: Update routes in `index.php`
3. **Create Views**: Add view templates in `resources/views/`
4. **Database Changes**: Create migrations if needed
5. **Test**: Verify functionality works as expected

## 🔒 Security Considerations

### Production Deployment

1. **Environment Variables**: Never commit `.env` files to version control
2. **File Permissions**: Ensure proper file permissions (644 for files, 755 for directories)
3. **Database Security**: Use strong passwords and limit database user permissions
4. **HTTPS**: Always use SSL/TLS in production
5. **Regular Updates**: Keep PHP and dependencies updated

### Security Features Implemented

- Password hashing using bcrypt
- SQL injection prevention through prepared statements
- XSS protection through output escaping
- CSRF token validation
- File upload validation and sanitization
- Session security with regeneration

## 🐛 Troubleshooting

### Common Issues

**1. Database Connection Error**
- Check database credentials in `.env`
- Ensure MySQL service is running
- Verify database exists

**2. File Upload Issues**
- Check directory permissions for `public/uploads/`
- Verify PHP upload settings (`upload_max_filesize`, `post_max_size`)

**3. View Not Found Error**
- Ensure view files exist in `resources/views/`
- Check file naming conventions (lowercase, underscores)

**4. Migration Errors**
- Check database connection
- Ensure migration files follow naming convention
- Verify SQL syntax in migration files

### Debug Mode

Enable debug mode by setting in `.env`:
```env
APP_ENV=development
```

This will show detailed error messages and stack traces.

## 📚 API Documentation

### Authentication Endpoints

- `POST /login` - User login
- `POST /register` - User registration
- `GET /logout` - User logout

### Property Endpoints

- `GET /properties` - List properties with filters
- `GET /properties/{id}` - Get property details
- `POST /properties` - Create new property (landlords only)
- `PUT /properties/{id}` - Update property (owner only)
- `DELETE /properties/{id}` - Delete property (owner only)

### Verification Endpoints

- `GET /verification` - Verification dashboard
- `POST /verification/upload-document` - Upload verification document
- `POST /verification/process-biometric` - Process biometric verification

### API Endpoints

- `GET /api/properties` - Get properties (JSON)
- `POST /api/messages` - Send message
- `GET /api/messages/{conversationId}` - Get conversation
- `POST /api/save-property` - Save/unsave property
- `POST /api/report-property` - Report suspicious property

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 📞 Support

For support and questions:
- Email: Coming Soon...
- Documentation: [Coming Soon...](...)

## 🚀 Deployment

### Production Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Configure proper database credentials
- [ ] Set up SSL certificate
- [ ] Configure web server (Apache/Nginx)
- [ ] Set proper file permissions
- [ ] Configure backup strategy
- [ ] Set up monitoring and logging
- [ ] Test all functionality
- [ ] Configure verification service APIs (Jumio/Onfido)

### Recommended Hosting

- **VPS/Dedicated**: DigitalOcean, Linode, AWS EC2
- **Shared Hosting**: Ensure PHP 8.0+ and MySQL support
- **Database**: MySQL 8.0+ or MariaDB 10.5+

---

**Built with ❤️ for student safety and security**
