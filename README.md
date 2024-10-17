# Real Estate CRM prototype built in Symfony
This Real Estate CRM prototype is built using the Symfony framework and incorporates several key features to streamline real estate management processes. It leverages design patterns, external API integration, and asynchronous task handling to enhance efficiency and scalability.

## Key Feature

### Property Valuation
Strategy Pattern: Implements the Strategy Pattern to provide flexibility in property valuation methods. Different valuation strategies can be easily added or removed without modifying existing code.

### Concurrent API Requests
Asynchronous Requests: Makes concurrent requests to multiple external APIs to gather data from various sources, improving performance and reducing latency.

### Property Validation
Multi-Step Form: Utilizes a multi-step form to validate property data at different stages of the submission process.

External API Validation: Integrates with external APIs to validate property information, ensuring data accuracy and consistency

### PDF Generation
Property Documentation: Generates comprehensive PDF documents for properties, including details, photos, and relevant information.

### Email Integration
Email Platform Integration: Connects to an email platform to send notifications, marketing campaigns, and other communications.

### Asynchronous Task Handling
Background Processing: Offloads long-running tasks to the background using asynchronous task handling, improving application responsiveness and performance.

### Internationalization
Language Settings: Provides support for multiple languages, allowing users to select their preferred language for the user interface.
