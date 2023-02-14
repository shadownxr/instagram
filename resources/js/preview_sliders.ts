// import '@splidejs/splide/css';
//
// import Splide from '@splidejs/splide';
//
// const DESKTOP = 0;
// const MOBILE = 1;
// let url = 'https://prestashop1788.local/pl/module/instagram/ajax';
//
// function fetchSettings() {
//     return $.ajax({
//         type: 'POST',
//         url: url,
//         cache: false,
//         data: {
//             method : 'test',
//             ajax: true
//         },
//         success: function () {
//         }
//     });
// }
//
// jQuery(() => {
//     let image_size = $('input[name="image_size"]');
//     let perPage: number;
//     let size: number = image_size.val() as number;
//     let gap: number;
//
//     console.log("ME WORK");
//
//     let options = {
//         type: 'slide',
//         perPage: 2,
//         perMove: 1,
//         //width: (2 * size) + Number(15),
//         autoWidth: true,
//         gap: Number(15),
//     };
//
//     let desktop_slider = new Splide( '#preview_desktop_slider', options);
//     desktop_slider.mount();
//
//     image_size.on('input',function(){
//         let size = $('input[name="image_size"]').val() as number;
//         $('.images').width(size).height(size);
//         desktop_slider.destroy();
//         /*desktop_slider.options = {
//             width: (2 * size) + Number(15),
//         }*/
//         desktop_slider.mount();
//     });
// });
//
// function getOptions(response: any, type: number) {
//     let options = {
//         type: 'slide',
//         perPage: response[type].images_per_gallery,
//         perMove: 1,
//         width: (response[type].images_per_gallery * response[type].image_size) + Number(response[type].gap),
//         gap: Number(response[type].gap),
//     };
//
//     return options;
// }
//
// /*$.when(fetchSettings()).done(function(response){
//     response = JSON.parse(response);
//
//     let options = getOptions(response, DESKTOP);
//     new Splide( '#preview_desktop_slider', options).mount();
//
//     let mobile_options = getOptions(response, MOBILE);
//     new Splide( '#preview_mobile_slider', mobile_options).mount();
// });*/
