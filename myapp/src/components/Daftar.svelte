<script>

import { createEventDispatcher } from "svelte";
    let dispatch = createEventDispatcher();

export let mybaseurl;
export let currentuserid;
export let luqchatid;
export let displaylogin = "hidden"; 

function loginurl(){
  window.location.href = `${mybaseurl}/wp-admin`;
  return;
}

function cancellogin(){
  displaylogin = "hidden" ; 
  dispatch("cancellogin", "hidden");
  
}


async function proceedpost(){
  let anonymousName = document.getElementById("yourname") ;

  dispatch("proceedpost", anonymousName);
  dispatch("cancellogin", "hidden");
  displaylogin = "hidden" ; 

  // let getmessage = document.getElementById("yourMessage");
  // console.log('currentuserid',getmessage.value);
  // let mymessage = new Promise(function (myResolve, myReject) {

  //           fetch(`${mybaseurl}/wp-json/myplugin/v1/author/1/?sendMessage=${btoa(getmessage.value)}&currentuserid=${currentuserid}&device_id=${luqchatid}&anonymousName=${anonymousName}`)
  //             .then(response => response.text())
  //             .then(result => {
               
  //               myResolve(result);
  //               getMessage();
  //               storeiIdUser(luqchatid);
  //               setTimeout(function() {
  //                 updateScroll();
  //               }, 1000);
                

  //             })
  //             .catch(error => console.log('error', error));

  //         });

          
  //         getmessage.value = '' ; 

}


// $: console.log('mydisplaylogin', displaylogin);

</script>


<!-- This example requires Tailwind CSS v2.0+ -->
<div class="{displaylogin} relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!--
      Background backdrop, show/hide based on modal state.
  
      Entering: "ease-out duration-300"
        From: "opacity-0"
        To: "opacity-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100"
        To: "opacity-0"
    -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
  
    <div class="fixed z-10 inset-0 overflow-y-auto">
      <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
        <!--
          Modal panel, show/hide based on modal state.
  
          Entering: "ease-out duration-300"
            From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            To: "opacity-100 translate-y-0 sm:scale-100"
          Leaving: "ease-in duration-200"
            From: "opacity-100 translate-y-0 sm:scale-100"
            To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        -->
        <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <!-- Heroicon name: outline/exclamation-triangle -->
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v3.75m-9.303 3.376C1.83 19.126 2.914 21 4.645 21h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 4.88c-.866-1.501-3.032-1.501-3.898 0L2.697 17.626zM12 17.25h.007v.008H12v-.008z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Please login your account to continue or post comment as anonymous</h3>
                <div>
                  <label for="yourname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Your Anonymous Name</label>
                  <input type="text" id="yourname" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Your Name" required>
              </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button on:click={loginurl}   type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">Login</button>
            <button on:click={proceedpost}   type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">Post</button>
            <button on:click={cancellogin} type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>