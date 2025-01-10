@verbatim
<div class="p-4 flex justify-center items-center min-h-screen bg-gradient-to-r from-blue-600 via-purple-500 to-blue-800 "> 
  <div class="container flex justify-center"> 
          <div class="h-full lg:w-[35%] rounded-2xl bg-white p-4 shadow-2xl border-2 border-dashed  border-blue-400"> 
            <div class=" flex flex-col text-center "> 
              <h1 class=" text-3xl font-extrabold font-san">PT Tia Sentosa EMKL</h1>
              <h1 class="font-semibold">Welcome</h1>
              <h1 class="font-semibold">Please Sign in to your account!</h1>
            </div>
            <form @submit="onLogin">
              <div class="relative">
                <FieldX :bind="{ readonly: false, style: 'padding-top:20px; padding-bottom:20px; font-size: 11pt'}"
                  :value="values.email" @input="v=>values.email=v" placeholder="Username" fa-icon="user"
                  :check="false" />
              </div>
              <div class="relative">
                <FieldX :bind="{ readonly: false, style: 'padding-top:20px; padding-bottom:20px; font-size: 11pt'}"
                  :value="values.password" @input="v=>values.password=v" type="password" placeholder="Password"
                  fa-icon="lock" :check="false" />
              </div>
              <div class="relative py-5">
                <button type="submit" class="bg-blue-500 w-full text-white rounded-md px-2 py-2 hover:bg-blue-600 duration-300">Masuk</button>
              </div>
            </form>
          </div>

  </div>
</div>
@endverbatim