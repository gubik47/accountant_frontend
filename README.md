# Accountant frontend

Simple frontend for the Accountant app that tracks transactions of all your bank accounts uploaded via CSV files.

## Installation

`docker` and `docker-compose` need to be installed in order to run the app.

1. Follow the installation steps for the backend part of the app https://github.com/gubik47/accountant#readme. 
2. Add `127.0.0.1 accountant.local` to your local DNS records, eg. `/etc/hosts`.
3. Start the application containers: `docker-compose up -d`
4. Start a bash inside the builder container `docker exec -it accountant_builder bash`
5. Run these commands inside the container:
    1. `composer install`
    2. `yarn install`
    3. `yarn dev`

The app is now ready to be accessed at http://accountant.local/.
