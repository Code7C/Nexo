user_test=new RegExp("^[a-z0-9 ]{1,10}")
name_test=new RegExp("^[A-Za-z ]+$")
function ValidarForm()
        {
            u=document.getElementById("email").value;
            c=document.getElementById("password").value;
            
            mostrar=document.getElementById("mostrar").checked;
            if(!user_test.test(u))
            {
                document.getElementById("msjuser").innerHTML="El nombre de usuario no es válido.";
                document.getElementById("msjuser").style.backgroundColor="red"
                return false;
            }
            if(c=="")
            {
                document.getElementById("msjpass").innerHTML="Este campo es obligatorio.";
                document.getElementById("msjpass").style.backgroundColor="red";
                return false;
            }
            if(c.length<6 || c.length>16)
            {
                document.getElementById("msjpass").innerHTML="Entre 6 y 16 caracteres obligatorio.";
                document.getElementById("msjpass").style.backgroundColor="red";
                return false;
            }
            for(i in c)
            {
                if(c[i]==" ")
                {
                    document.getElementById("msjpass").innerHTML="La contraseña no puede contener espacios..";
                    document.getElementById("msjpass").style.backgroundColor="red";
                    return false;
                }
            }
        
        }