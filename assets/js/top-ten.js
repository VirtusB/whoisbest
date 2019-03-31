// var event = new MouseEvent('mouseenter');
//
// function mouseOverYou() {
//     var myTarget = document.getElementById('this-is-you');
//
//     try {
//         myTarget.dispatchEvent(event);
//     } catch (e) {
//
//     }
//
//     return myTarget;
// }
//
// window.addEventListener('load', function () {
//     var target = mouseOverYou();
//
//     ['mouseleave', 'mouseenter', 'mouseover', 'focusout', 'focus', 'click', 'mouseout', 'blur'].forEach(ev => {
//         target.addEventListener(ev, function () {
//             mouseOverYou();
//         });
//     });
// });


window.addEventListener('load', function () {
    document.getElementById('this-is-you').dispatchEvent(new MouseEvent('mouseenter'));
    // document.getElementById('this-is-you').addEventListener('mouseleave', function (e) {
    //     e.stopImmediatePropagation();
    // });
});


// var targetNode = document.body;
// var config = { attributes: true, childList: true, subtree: true };
//
// var callback = function(mutationsList) {
//     for(var mutation of mutationsList) {
//         if (mutation.type === 'childList') {
//             if (mutation.removedNodes.length === 1) {
//                 if (mutation.removedNodes[0].className === 'tooltip bs-tooltip-left false') {
//                     document.getElementById('this-is-you').dispatchEvent(new MouseEvent('mouseenter'));
//                 }
//             }
//         }
//     }
// };
//
// var observer = new MutationObserver(callback);
// observer.observe(targetNode, config);