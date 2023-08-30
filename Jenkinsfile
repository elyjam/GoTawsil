pipeline {
    agent any

    stages {
        stage('Clone') {
            steps {
                checkout scm
            }
        }

        stage('Build and Test') {
            steps {
                script {
                    def imageName = 'laravelapp'

                    // Build Docker image
                    sh "docker build -t ${imageName} ."

                    // Run tests within the Docker image
                    sh "docker run ${imageName} php artisan test"
                }
            }
        }

        stage('Deploy') {
            steps {
                script {
                    def imageName = 'laravelapp'

                    // Push Docker image to a registry (e.g., Docker Hub, AWS ECR)
                    sh "docker push ${imageName}"

                    // Deploy using Docker Compose
                    sh "docker-compose up -d"
                }
            }
        }
    }
}
