//affichage connexion & inscription
let afficherInscription = document.getElementById("span-inscription");
let blockInscription = document.getElementById("inscription");
let blockConnexion = document.getElementById("connexion");
afficherInscription.onclick = function(){
    blockInscription.style.display = "inline-block";
    blockConnexion.style.display = "none";
}
let afficherConnexion = document.getElementById("span-connexion");
afficherConnexion.onclick = function(){
    blockConnexion.style.display = "inline-block";
    blockInscription.style.display = "none";
}
//bonus malus
let bonusMalusForce = document.getElementById("bonusMalusForce");
let valueForce = document.getElementById("force");
setInterval("force()", 100);
function force(){
    let totalForce = Number(valueDexterite.value) + Number(valueAgilite.value) + Number(valueConstitution.value);
    valueForce.setAttribute("max", 40 - totalForce);
    bonusMalusForce.textContent = Number(valueForce.value) - 10;
}
let bonusMalusAgilite = document.getElementById("bonusMalusAgilite");
let valueAgilite = document.getElementById("agilite");
setInterval("agilite()", 100);
function agilite(){
    let totalAgilite = Number(valueForce.value) + Number(valueDexterite.value) + Number(valueConstitution.value);
    valueAgilite.setAttribute("max", 40 - totalAgilite);
    bonusMalusAgilite.textContent = Number(valueAgilite.value) - 10; 
}
let bonusMalusDexterite = document.getElementById("bonusMalusDexterite");
let valueDexterite = document.getElementById("dexterite");
setInterval("dexterite()", 100);
function dexterite(){
    let totalDexterite = Number(valueForce.value) + Number(valueAgilite.value) + Number(valueConstitution.value);
    valueDexterite.setAttribute("max", 40 - totalDexterite);
    bonusMalusDexterite.textContent = Number(valueDexterite.value) - 10; 
}
let bonusMalusConstitution = document.getElementById("bonusMalusConstitution");
let valueConstitution = document.getElementById("constitution");
setInterval("constitution()", 100);
function constitution(){
    let totalConstitution = Number(valueForce.value) + Number(valueAgilite.value) + Number(valueDexterite.value);
    valueConstitution.setAttribute("max", 40 - totalConstitution);
    bonusMalusConstitution.textContent = Number(valueConstitution.value) - 10; 
}
// animation loader de la page
let loader = document.querySelector(".loader");
window.addEventListener("load", () => {
    loader.classList.add("transition-loader");
    loader.style = "z-index: -10";
})