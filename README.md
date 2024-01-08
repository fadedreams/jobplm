#### Laravel Job Portal Application

Welcome to the Laravel job portal application, a comprehensive and innovative solution for streamlining job management and hiring processes. Below is an overview of the key features and technologies employed in this project.
Microservices Architecture with Kafka

    The application leverages a microservices architecture, consisting of the Laravel job portal (jobp) and an Golang email service (email_go).
    Kafka, a distributed event streaming platform, facilitates efficient communication between microservices, ensuring seamless data exchange.

##### Jobp Laravel App Features

    The Laravel job portal enhances job management and hiring processes, incorporating user-friendly features.
    Stripe integration enables secure and convenient payment transactions within the platform.
    Users can upload resumes, providing job owners with valuable insights into applicants' profiles.

##### Email Notification System

    Kafka is utilized to communicate with the Golang email service (email_go) for sending important notifications and emails to users.
    This decoupled architecture ensures a scalable and resilient system, enabling effective communication without impacting the core functionalities of the job portal.

Caching with Redis

    Redis, an in-memory data structure store, is employed to cache specific endpoints within the job portal.
    Caching enhances performance by storing frequently accessed data in memory, reducing response times for users.

Docker Integration

    Docker is utilized for containerization, ensuring a consistent and portable environment across development, testing, and production.
    The docker-compose.yml file defines Docker services, including Redis and Kafka, streamlining deployment and scaling.
