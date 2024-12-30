# Accessibility Analyzer

A professional web application that analyzes HTML files for WCAG 2.1 compliance using Laravel and Vue.js.

## Features

- Upload and analyze HTML files for accessibility issues
- Get detailed reports with WCAG compliance scores
- View specific issues with WCAG guideline references
- Receive actionable suggestions for improvements
- Real-time analysis with instant feedback

## Tech Stack

### Backend
- PHP 8.1+
- Laravel 10
- PHPUnit for testing

### Frontend
- Inertia.js with React
- TypeScript
- Tailwind CSS
- Vite

## Quick Start

### Prerequisites
- PHP 8.1+
- Node.js 16+
- Composer

### Installation

1. Clone the repository:
```bash
git clone https://github.com/code-monarch/laravel-assessment
cd laravel-assessment
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```

5. Start development servers:
In 1st terminal (Laravel): 
```bash
composer run dev
```

## Architecture

### Backend Components

1. **Controllers**
   - `AccessibilityController`: Handles file upload and analysis
   - `Api/V1/AccessibilityController`: API endpoints for analysis

2. **Services**
   - `AccessibilityAnalyzer`: Core analysis engine
   - `AccessibilityScoreCalculator`: Scoring logic
   - `Rules/*`: Modular WCAG rule implementations

3. **Configuration**
   - Configurable WCAG version and conformance level
   - Customizable scoring system
   - Flexible file upload settings

### Frontend Components

1. **Core Features**
   - File upload with drag-and-drop support
   - Real-time analysis feedback
   - Detailed issue reporting

2. **UI Components**
   - Responsive design
   - Clear issue presentation
   - WCAG guideline references

## Testing

Run the test suite:
```bash
# Backend tests
php artisan test

# Frontend tests
npm run test
```

## Scoring System

The accessibility score is calculated using:
- Base score: 100 points
- Error deduction: -10 points per error
- Warning deduction: -5 points per warning

## API Documentation

### Analyze HTML File
```http
POST /api/v1/analyze
Content-Type: multipart/form-data

file: <html-file>
```

Response:
```json
{
  "score": 85,
  "issues": [
    {
      "type": "error",
      "message": "Image missing alt attribute",
      "element": "/html/body/img[1]",
      "wcagRef": {
        "id": "1.1.1",
        "url": "https://www.w3.org/WAI/WCAG21/Understanding/non-text-content",
        "level": "A"
      }
    }
  ],
  "summary": {
    "totalIssues": 1,
    "errors": 1,
    "warnings": 0
  }
}
```

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

MIT