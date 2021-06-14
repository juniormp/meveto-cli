# Project setup 

### Execute the project
1. Run Command:
    ```
     php [meveto-cli]
    ```
    
3. Change the MEVETO_BACKEND_URL env param on env file to point to the local url where the meveto-backend was started.

2. Commands:
   ```
     php [meveto-cli] register mauricio
     php [meveto-cli] get-server-key
     php [meveto-cli] store-secret mauricio app_test test_value
     php [meveto-cli] get-secret mauricio app_test
    ```
