function alertToast(title = 'success', type ='error', timer = 5000){
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: timer
  });
  
  Toast.fire({
    type: type,
    title: title
  })
}

function successToast(){
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });
  
  Toast.fire({
    type: 'success',
    title: 'Signed in successfully'
  })
}