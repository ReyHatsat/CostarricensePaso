

// estructura del request resquestAsync.
// request muestra Loader y bloquea pantalla, solo un request a la vez.
// requestAsync no y permite ejecutar varios requestAsync a la vez.


//request de prueba SIN PARAMETROS
// requestAsync( '<?=PATH_API?>person/read.php' , function(r){
//   console.log(r)
// });
//
//
// //request de prueba parametros GET
// const params = `key=Sergio`;
// requestAsync( `<?=PATH_API?>person/search.php?${params}` , function(r){
//   console.log(r)
// });
//
//
// //request de prueba con parametros POST
// let config = { data:{
//     id_person:3
// }}




//test();

// ASYNC AWAIT
async function test(){

  const params = `key=Sergio`;
  await request( `<?=PATH_API?>person/search.php?${params}` , function(r){
    //funcionbusqueda()
    console.log(r)
    return;
  });

  await request( `<?=PATH_API?>person/search.php?${params}` , function(r){
    console.log(r)
    return;
  });

  await request( `<?=PATH_API?>person/search.php?${params}` , function(r){
    console.log(r)
    return;
  });

}
