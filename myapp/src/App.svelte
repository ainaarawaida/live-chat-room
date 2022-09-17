


<script>
  import "./app.css";

  let luqscript = document.createElement("script"); // create a script DOM node
  luqscript.src = `${import.meta.env.BASE_URL}script/md5.js`; // set its src to the provided URL
  document.head.appendChild(luqscript);

                     
  //   let luqscript2 = document.createElement("script"); // create a script DOM node
  // luqscript2.src = `${import.meta.env.BASE_URL}script/test.js`; // set its src to the provided URL
  // luqscript2.setAttribute('type', 'module');
  // document.body.appendChild(luqscript2);

 

  import { onDestroy } from "svelte";
  import { onMount } from "svelte";
  import Daftar from "./components/Daftar.svelte";
  import { EmojiButton } from '@joeattardi/emoji-button';



  let PollStores = [];
  let prevNowPlaying ; 
  let activeUser = []  ;
  let activeUserindex = -1 ;
  let luqchatid =  localStorage.getItem("luqchatid");
  let displaylogin = 'hidden'; 
  let currentuserid = (import.meta.env.MODE !== 'development' ? frontend_ajax_object.statuslogin.data.ID : 0) ; 
  let mybaseurl = (import.meta.env.MODE !== 'development' ? window.location.origin : 'http://test.test') ; 

  let fileVar;
  let hideimg = "hidden"; 
  let luqtempname =  localStorage.getItem("luqtempname");
   let getmessage = document.getElementById("yourMessage");



  var requestOptions = {
    method: 'GET',
    redirect: 'follow'
  };

onMount(async () => {
  // console.log("yasalam");
  getMessage();
  storeiIdUser(luqchatid);
  getactiveuser();
 
  setInterval(function () {
    getactiveuser();
      }, 20000);
  
  

});



  function updateScroll() {
    var element = document.getElementById("yourDivID");
    element.scrollTop = element.scrollHeight;
   
  }


  let hidden1, hidden2 = "hidden";

  function handleClick(ar1) {
    
    if (ar1 === "small") { //buka cahatbox
      hidden1 = "hidden";
      hidden2 = "";

      setTimeout(function() {
        updateScroll();
      }, 100);

      prevNowPlaying = setInterval(function () {
        getMessage();
        updateScroll();
      
      }, 25000);


      // setTimeout(function() {
      //   updateScroll();
      // }, 100);
      
    } else {   //tutup chatbox
      clearInterval(prevNowPlaying);
      hidden2 = "hidden";
      hidden1 = "";
    }
  }



  async function getMessage(){

    let ticket = new Promise(function (myResolve, myReject) {

      fetch(`${mybaseurl}/wp-json/myplugin/v1/author/1/?getMessage=1`)
        .then(response => response.text())
        .then(result => myResolve(result))
        .catch(error => console.log('error', error));

      });

      PollStores = JSON.parse(await ticket);
      

  }



  async function sendMessage(e){

    luqtempname =  localStorage.getItem("luqtempname");
    // console.log('luqtempname',luqtempname);
   
    if((currentuserid === 0 || currentuserid === undefined) &&  e.detail.value === undefined && luqtempname === null){
        alert("please Login First !!") ;
        displaylogin = "" ; 
        


    }else{

      if(e.detail.value){
        localStorage.setItem("luqtempname", e.detail.value);
      }


            let getmessage = document.getElementById("yourMessage");
            const dataArray = new FormData();
            dataArray.append("actionname", 'sendMessage');
            dataArray.append("sendmessage", getmessage.value);
            dataArray.append("currentuserid", currentuserid);
            dataArray.append("device_id", luqchatid);
            dataArray.append("anonymousName", localStorage.getItem("luqtempname"));

            // console.log('fileVar[0]', fileVar);
            if(fileVar != undefined){
              dataArray.append("uploadFile", fileVar[0]);
            }
            // console.log('getmessage.value', getmessage.value);
            if(getmessage.value === '' && fileVar === undefined){
              // console.log('yalama', fileVar);
              return ;
            }
              
            let url = `http://test.test/wp-json/api/v1/data` ; 

            // POST request using fetch()

            let mymessage = new Promise(function (myResolve, myReject) {
              fetch(url, {
                  // Adding method type
                  method: "POST",
                  // Adding body or contents to send
                  body: dataArray,
              })
                
              // Converting to JSON
              .then(response => response.json())
                
              // Displaying results to console
              .then(result => {
                // console.log('result',result);
                myResolve(result);
                getMessage();
                delfileupload();
                storeiIdUser(luqchatid);
                

                setTimeout(function() {
                      updateScroll();
                    }, 500);

              })
              .catch(error => console.log('error', error));

            });

            // console.log('cccc', await mymessage);
            
            getmessage.value = '' ; 
            

         
     

    }

  

  }


  async function getactiveuser(){
    let result = new Promise(function (myResolve, myReject) {

    fetch(`${mybaseurl}/wp-json/myplugin/v1/author/1/?getactiveuser=1`)
      .then(response => response.text())
      .then(result => myResolve(result))
      .catch(error => console.log('error', error));

    });

    activeUser = JSON.parse(await result) ; 
    // console.log('activeUser',activeUser);

   
  }


  function storeiIdUser(luqchatid){

    luqchatid =  localStorage.getItem("luqchatid");
    
    // console.log('luqchatid', luqchatid);
   
    if(luqchatid === null){
     
      let newluqchatid = (Math.floor(Math.random() * 100) + Date.now()).toString() ; 
      localStorage.setItem("luqchatid", newluqchatid);
      fetch(`${mybaseurl}/wp-json/myplugin/v1/author/1/?storeIdUser=${newluqchatid}&currentuserid=${currentuserid}`)
        .then(response => response.text())
        .then(result => (result))
        .catch(error => console.log('error', error));

    }else{
      fetch(`${mybaseurl}/wp-json/myplugin/v1/author/1/?updateIdUser=${luqchatid}&currentuserid=${currentuserid}`)
        .then(response => response.text())
        .then(result => (result))
        .catch(error => console.log('error', error));

    }

  }


  function tabChange(e){
    displaylogin = e.detail ;
   
  }


  const fileupload = (e) => {
    // console.log(fileVar);
    var image = document.getElementById('output');
	  image.src = URL.createObjectURL(e.target.files[0]);

    hideimg = ""  ;
    
  }

  const delfileupload = (e) => {
   
    fileVar = "" ;
    hideimg = "hidden"  ;
    
  }



    

    document.addEventListener("DOMContentLoaded", function(){

      const picker = new EmojiButton();
    const trigger = document.querySelector('#emoji-trigger');

    picker.on('emoji', selection => {
      // handle the selected emoji here
         
          getmessage = document.getElementById("yourMessage");
          getmessage.value += selection.emoji ;

          //  console.log('getmessage', getmessage);
          
    });

  
      trigger.addEventListener('click', () => picker.togglePicker(trigger));
    });

  
    let getprofilepic = (mail) => {

      if(mail){
        let useremail = window.md5(mail.toLowerCase());
        return `<img alt="" src="http://1.gravatar.com/avatar/${useremail}?s=32&amp;d=mm&amp;r=g" srcset="http://1.gravatar.com/avatar/${useremail}?s=64&amp;d=mm&amp;r=g 2x" class="avatar avatar-32 photo" loading="lazy" width="32" height="32">` ;

      }else{
        return `<img alt="" src="http://1.gravatar.com/avatar/aa6e2a9477c20add7970e676b95ab7f4?s=32&amp;d=mm&amp;r=g" srcset="http://1.gravatar.com/avatar/aa6e2a9477c20add7970e676b95ab7f4?s=64&amp;d=mm&amp;r=g 2x" class="avatar avatar-32 photo" loading="lazy" width="32" height="32">` ;

      }
     
    }


    console.log('activeUser',activeUser);
    console.log('activeUser',activeUser.length);

</script>



<Daftar on:proceedpost={sendMessage} mybaseurl={mybaseurl} displaylogin={displaylogin} on:cancellogin={tabChange} currentuserid={currentuserid} luqchatid={luqchatid}  />


<div
  class="flex w-14 h-10 fixed right-0 bottom-0 m-5 pt-px pl-2 border-4 border-black bg-black {hidden1}"
  on:click={() => handleClick("small")}
>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
    stroke-width="1.5"
    stroke="currentColor"
    class="w-6 h-6 fill-white"
  >
    <path
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
    />
  </svg>
  <span class="text-white">{activeUser.length}</span>
</div>

<div
  class="container w-96 h-96 fixed right-0 bottom-0 m-5 border-4 border-black bg-white {hidden2}"
>
  <div class="relative flex items-center p-3 border-b border-gray-300">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
      stroke-width="1.5"
      stroke="currentColor"
      class="w-6 h-6"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"
      />
    </svg>

    <span class="basis-5/6 block ml-2 font-bold text-gray-600">Chat Box</span>
    <div class="flex basis-1/8 pr-2">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        class="w-6 h-6"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
        />
      </svg>
      {activeUser.length}
    </div>

    <div class="basis-1/8" on:click={handleClick}>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.5"
        stroke="currentColor"
        class="w-6 h-6"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
        />
      </svg>
    </div>
  </div>


  
<!--- start comment-->
  <div id="yourDivID" class="overflow-y-scroll h-64 flex-col-reverse">
    {#each PollStores as PollStore, i}
    <div class="relative flex items-center p-1 border-b border-gray-300">
      {@html getprofilepic(PollStore.user_email)}
      <!-- <img
        class="object-cover w-8 h-8 rounded-full"
        src="https://cdn.pixabay.com/photo/2018/01/15/07/51/woman-3083383__340.jpg"
        alt="username"
      /> -->
      <!-- <span class="absolute ml-11 font-bold text-gray-600 text-xs top-1">Testing</span> -->
      {
         #if activeUser.map((o) => o.device_id).indexOf(PollStore.device_id) != -1 
      }
        <span class="relative w-3 h-3 bg-green-600 rounded-full -left-1 -top-3" />
      {:else}
       <span class="relative w-3 h-3 bg-orange-600 rounded-full -left-1 -top-3" />
      {/if}
     
      <div class="flex ml-2">
        <span class="relative left-0">
          <span class="text-black-700 font-bold">{PollStore.anonymousName}</span>
          <div class="text-gray-700 bg-gray-100 rounded shadow">{@html PollStore.text}</div>
          <!-- <textarea class="text-gray-700 bg-gray-100 rounded shadow">{PollStore.text}</textarea> -->
    

          {#if PollStore.guid != null}
        
          <a target="_blank" href={PollStore.guid}><img src={PollStore.guid} width="100" /></a>
          {/if}
        </span>
        <span class="absolute text-black-300 text-xs p-2 right-0">
          {PollStore.created}
        </span>
      
      </div>
    </div>

    {/each}

    
  </div>
  <!--- end comment-->
  <div
    class="flex w-full p-3 border-t absolute bottom-12 bg-white {hideimg}"
  >

  
    <img id="output" width="100" />
    <svg on:click={delfileupload} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
      <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
    </svg>
  </div>

  <div
    class="flex items-center justify-between w-full p-3 border-t border-gray-300 relative bottom-0 bg-white"
  >
    <button >
      <svg id="emoji-trigger"
        xmlns="http://www.w3.org/2000/svg"
        class="w-6 h-6 text-gray-500"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
        />
      </svg>
    </button>
    <button for="file-input" style="padding-left:4px;">
      
        <label for="file-input">
          <img style="width:50px;" src="https://icons.iconarchive.com/icons/dtafalonso/android-lollipop/128/Downloads-icon.png"/>
        </label>
      
        <input id="file-input" type="file" bind:files={fileVar} style="display: none;" on:change={fileupload}/>
    </button>
    <textarea id="yourMessage" name="message" rows="1" class="block w-full pl-4 mx-3 bg-gray-100 rounded-full outline-none focus:text-gray-700" required></textarea>
    <!-- <input
      type="text" id="yourMessage"
      placeholder="Message"
      class="block w-full py-2 pl-4 mx-3 bg-gray-100 rounded-full outline-none focus:text-gray-700"
      name="message"
      required
    /> -->
    <!-- <button>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="w-5 h-5 text-gray-500"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"
        />
      </svg>
    </button> -->
    <button type="submit" on:click={sendMessage}>
      <svg
        class="w-5 h-5 text-gray-500 origin-center transform rotate-90"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
        fill="currentColor"
      >
        <path
          d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"
        />
      </svg>
    </button>
  </div>
</div>

<style>
</style>



