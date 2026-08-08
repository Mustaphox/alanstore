const header=document.getElementById('header');
window.addEventListener('scroll',()=>header.classList.toggle('scrolled',scrollY>40));
const search=document.querySelector('.search-panel');
document.querySelector('.search-trigger').onclick=()=>search.classList.add('open');
document.querySelector('.close-search').onclick=()=>search.classList.remove('open');
const toast=document.querySelector('.cart-toast'), bag=document.querySelector('.bag b');
document.querySelectorAll('.quick-add').forEach(button=>button.onclick=()=>{bag.textContent=+bag.textContent+1;toast.classList.add('show');setTimeout(()=>toast.classList.remove('show'),2800)});
document.querySelector('.cart-toast span').onclick=()=>toast.classList.remove('show');
document.querySelectorAll('.wish').forEach(button=>button.onclick=()=>{button.textContent=button.textContent==='♡'?'♥':'♡';button.style.color=button.textContent==='♥'?'#b49456':''});
