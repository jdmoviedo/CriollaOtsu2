Inputmask.extendAliases({
  myDecimal: {
    alias: "numeric",
    digits: 3,
    allowPlus: false,
    allowMinus: false,
    radixPoint: ",",
    //min: 30,
    //max: 400
  },
  numerico: {
    alias: "numeric",
    digits: 0,
    allowPlus: false,
    allowMinus: false,
    rightAlign: false,
    min: 60,
    max: 250,
  },
  cedula: {
    alias: "numeric",
    digits: 0,
    allowPlus: false,
    allowMinus: false,
    rightAlign: false,
  },
  registros: {
    alias: "numeric",
    digits: 0,
    allowPlus: false,
    allowMinus: false,
    rightAlign: false,
  },
  numero: {
    alias: "numeric",
    digits: 0,
    allowPlus: false,
    allowMinus: false,
    rightAlign: false,
  },
  numerodecimal: {
    alias: "numeric",
    digits: 3,
    allowPlus: true,
    allowMinus: false,
    rightAlign: false,
    min: 0.18,
    max: 2000,
  },
  numerofuma: {
    alias: "numeric",
    digits: 3,
    allowPlus: true,
    allowMinus: false,
    rightAlign: false,
    min: 0,
    max: 100,
  },
  decimal: {
    alias: "numeric",
    digits: 3,
    allowPlus: true,
    allowMinus: true,
    rightAlign: false,
    // radixPoint: ',',
  },
  decimalPtPtt: {
    alias: "numeric",
    digits: 1,
    allowPlus: false,
    allowMinus: false,
    radixPoint: ",",
  },
  decimalInr: {
    alias: "numeric",
    digits: 2,
    allowPlus: false,
    allowMinus: false,
    radixPoint: ",",
  },
  decimalElectrolitos: {
    alias: "numeric",
    digits: 1,
    allowPlus: false,
    allowMinus: false,
    radixPoint: ",",
  },
  decimalPet: {
    alias: "numeric",
    digits: 4,
    allowPlus: false,
    allowMinus: false,
    radixPoint: ",",
  },
  decimalElectrolitos2: {
    alias: "numeric",
    digits: 2,
    allowPlus: false,
    allowMinus: false,
    radixPoint: ",",
  },
  decimalRelac: {
    alias: "numeric",
    digits: 2,
    allowPlus: false,
    allowMinus: false,
    radixPoint: ",",
  },
  horaExtra: {
    alias: "numeric",
    digits: 2,
    allowPlus: false,
    allowMinus: false,
    radixPoint: ".",
  },
  decimalPoint: {
    alias: "numeric",
    digits: 3,
    allowPlus: false,
    allowMinus: false,
    radixPoint: ".",
    //min: 30,
    //max: 400
  },
  decimalOne: {
    alias: "numeric",
    digits: 1,
    allowPlus: false,
    allowMinus: false,
    radixPoint: ",",
  },
  decimalOneEsper: {
    alias: "numeric",
    digits: 1,
    allowPlus: false,
    allowMinus: false,
    radixPoint: ".",
  },
});

$(".decimal-punto").inputmask("myDecimal");
$(".decimal-coma").inputmask("decimalPoint");
$(".input-ip").inputmask("ip");
$(".numerico").inputmask("numerico");
$(".cedula").inputmask("cedula");
$(".classRegistros").inputmask("registros");
$(".numero").inputmask("numero");
$(".numerofuma").inputmask("numerofuma");
$(".decimal-ptptt").inputmask("decimalPtPtt");
$(".decimal-inr").inputmask("decimalInr");
$(".decimal-electrolitos").inputmask("decimalElectrolitos");
$(".decimal-pet").inputmask("decimalPet");
$(".decimal-electrolitos2").inputmask("decimalElectrolitos2");
$(".decimal-relac").inputmask("decimalRelac");
$(".horaextra").inputmask("horaExtra");
$(".decimal-one").inputmask("decimalOne");
$(".decimal-one-esper").inputmask("decimalOneEsper");
// $('.decimal').inputmask('decimal');
