{{-- Document Show View --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ahobila Two Step Verification</title>
</head>
<body>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700&display=swap");

html {
  background-color: #FF9800;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  text-align: center;
  font-family: "Lato", sans-serif;
}

section {
  display: flex;
  align-items: center;
  flex-direction: column;
  justify-content: space-around;
  width: 40vw;
  min-width: 350px;
  height: 55vh;
  background-color: white;
  border-radius: 12px;
  box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px,
    rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
  padding: 24px 0px;
}
svg {
  margin: 16px 0;
}
title {
  font-size: 20px;
  font-weight: bold;
}

p {
  color: #a3a3a3;
  font-size: 14px;
  width: 200px;
  margin-top: 4px;
}
input {
  width: 32px;
  height: 32px;
  text-align: center;
  border: none;
  border-bottom: 1.5px solid #d2d2d2;
  margin: 0 10px;
}

input:focus {
  border-bottom: 1.5px dotted #FF9800;
  outline: none;
}

button {
  width: 250px;
  letter-spacing: 2px;
  margin-top: 24px;
  padding: 12px 16px;
  border-radius: 8px;
  border: none;
  background-color: #387589;
  color: white;
  cursor: pointer;
}
    </style>
    <section>
        {{-- <div class="title">Ve</div> --}}
        <img src="https://ahobila.kods.app/assets/logo/logo.jpg" width="100%" height="175" viewBox="0 0 292 208" >
        {{-- <svg width="250" height="200" viewBox="0 0 292 208" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="74" cy="139" r="5" fill="#FF91B9" />
            <circle cx="79" cy="43" r="5" fill="#91E5FF" />
            <circle cx="188" cy="203" r="5" fill="#FF9191" />
          </svg>
          <circle cx="220" cy="15" r="5" fill="#FFC691" />
          <circle cx="119.606" cy="5" r="5" fill="#91FFAF" />
          <rect x="250.606" y="163" width="10" height="10" rx="1" fill="#E991FF" />
          <rect x="274" y="47.0925" width="10" height="10" rx="1" transform="rotate(-24.1576 274 47.0925)" fill="#FF9191" />
          <rect y="68.5666" width="10" height="10" rx="1" transform="rotate(-27.1716 0 68.5666)" fill="#91A3FF" />
          <path d="M33.0121 175.265L40.7499 180.821L32.0689 184.744L33.0121 175.265Z" fill="#FF9191" />
          <path d="M15.077 128.971L16.567 138.38L7.67356 134.966L15.077 128.971Z" fill="#FD91FF" />
          <path d="M286.447 120.204L287.505 129.672L278.777 125.854L286.447 120.204Z" fill="#FF91BF" />
          <defs>
            <clipPath id="clip0_1_45">
              <rect width="179" height="179" fill="white" transform="translate(62.6058 29)" />
            </clipPath>
          </defs>
         
        </svg> --}}
        <div class="title">Hello,  {{ $receiverName }}</div>
    
       <div class="title"> We have sent a verification code
          to your Email Id attached with this page URL  
          {{-- <u>{{ substr($receiverEmail, 0, 3) }}...{{ substr($receiverEmail, -5) }}</u> --}}
        </div>
      
        
          <form method="POST" action="{{ route('otp.verify', ['token' => $token]) }}">
            @csrf
            <div >
            <input type="text" name="otp" pattern="\d{4}" maxlength="4" name="otp"  placeholder="Enter 4 digit otp" style="width:10vw;border:1px solid black">
          </div>
            {{-- <div id='inputs'>
                <input id='input1' type='text' inputmode="numeric" pattern="[0-9]*" maxLength="1" />
                <input id='input2' type='text' inputmode="numeric" pattern="[0-9]*" maxLength="1" />
                <input id='input3' type='text' inputmode="numeric" pattern="[0-9]*" maxLength="1" />
                <input id='input4' type='text' inputmode="numeric" pattern="[0-9]*" maxLength="1" />
            
                <input type="hidden" id="otp" name="otp" />
            </div> --}}
            <button type="submit">Submit</button>
        </form>
       
  
<!-- Blade Template for Sending OTP -->

@if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
@if($errors->has('otp'))
<div class="error">{{ $errors->first('otp') }}</div>  @endif
      </section>
      <script>
        const inputs = document.querySelectorAll('#inputs input');
        const otpInput = document.getElementById('otp');
    
        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                // Ensure the input is numeric and has only one character
                input.value = input.value.slice(0, 1).replace(/[^0-9]/g, '');
    
                // Move to next input if current is filled
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
    
                updateOtpValue();
            });
    
            input.addEventListener('keydown', (event) => {
                if (event.key === 'Backspace' && index > 0) {
                    setTimeout(() => {
                        // Clear previous input and move focus back on Backspace
                        if (input.value === '') {
                            inputs[index - 1].value = '';
                            inputs[index - 1].focus();
                        }
                        updateOtpValue();
                    }, 10); // Short delay to handle input value update
                }
            });
        });
    
        function updateOtpValue() {
            // Check if all fields are filled
            if (Array.from(inputs).every(i => i.value.length === 1)) {
                otpInput.value = Array.from(inputs).map(i => i.value).join('');
            } else {
                otpInput.value = ''; // Clear OTP value if not all fields are filled
            }
    
            // Logging for debugging - can be removed later
            console.log('Current OTP Value:', otpInput.value);
        }
    </script>
    
</body>
</html>
