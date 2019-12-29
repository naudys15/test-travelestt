// var mi_viaje_sidebar=false;
// var login=false;
// var carousel=$('.owl-carousel');


// // mapa
// var mapa_cerca_de_mi=document.getElementById('mapid');
// // console.log(mapa_cerca_de_mi);
// if(mapa_cerca_de_mi){
//   var mymap = L.map('mapid').setView([38.7054500, -0.4743200], 10);

//   L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//       attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
//   }).addTo(mymap);
// }

// // carousel
// if(carousel!=undefined){
//   carousel.owlCarousel({
//     loop:true,
//     margin:10,
//     responsiveClass:true,
//     responsive:{
//         0:{
//             items:1,
//             nav:true
//         },
//         600:{
//             items:3,
//             nav:false
//         },
//         1000:{
//             items:4,
//             nav:true,
//             loop:false
//         }
//     }
//   });
// }

// // funcionamiento mi viaje sidebar
// $('#mis_viajes').on('click',function(){
//   if(mi_viaje_sidebar){
//     $('#mi_viaje_sidebar').removeClass('sidebar_on');
//     mi_viaje_sidebar=false;
//   }else{
//     $('#mi_viaje_sidebar').addClass('sidebar_on');
//     mi_viaje_sidebar=true;
//   }
// })

    // // funcionamiento login
    // $('#login_icon').on('click',function(){
    //     if(login){
    //       $('#login').css('display','none');
    //       login=false;
    //     }else{
    //       $('#login').css('display','block');
    //       login=true;
    //     }
    //   })
