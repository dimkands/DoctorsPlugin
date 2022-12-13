window.addEventListener("load", function() {

    console.log(document.querySelector("input#doctors-input").value);    

    const addButton = document.querySelector(".add-button");
    

    var boxes = document.getElementsByClassName("doctor-name-box");
    var buttons = document.getElementsByClassName("remove");


    let containerDiv = document.querySelector('.by-doctors-box-spans');
    for(i=0; i<boxes.length; i++){
        document.querySelector("#remove_" + i).addEventListener('click', function(){
            let removeEl = this.parentNode;                
            containerDiv.removeChild(removeEl);
        })
        document.querySelector("#remove_" + i).addEventListener('click', change_value);
    }

    var doctorBoxes = document.getElementsByClassName("doctor-name");




    addButton.addEventListener("click", add_doctor);


    function change_value(){
        var i=0;
        var currentValue = document.querySelector("input#doctors-input").value;
        currentValue = '';
        var existingDoctors = document.getElementsByClassName("doctor-name");
        for (i=0; i<existingDoctors.length; i++){
            if (i == existingDoctors.length - 1){
                currentValue = currentValue + existingDoctors[i].textContent;
            }
            else{
                currentValue = currentValue +  existingDoctors[i].textContent + ',';
            }
        }
        document.querySelector("input#doctors-input").value = currentValue;
        console.log(currentValue);
        if (currentValue == ' '){
            document.querySelector("input#doctors-input").value = null;
        }
    }

    function add_doctor(event){


        console.log("add");

        var doctorSelection = document.getElementById("doctors-selection");
        var existingDoctors = document.getElementsByClassName("doctor-name");

        var selectedDoctor = doctorSelection.options[doctorSelection.selectedIndex].text;

            for (i=0; i<existingDoctors.length; i++){
                if (existingDoctors[i].innerHTML.includes(selectedDoctor)){
                    setTimeout(function() {
                        existingDoctors[i].style.backgroundColor = 'white'}, 100);
                    setTimeout(function() {
                        existingDoctors[i].style.backgroundColor = 'rgb(197, 196, 196)'}, 300);
                    return;
                }
            }

            var val = document.querySelector("input#doctors-input").value;


            if (val ==''){
                val = selectedDoctor;
            }
            else{
                val = val + ','+ selectedDoctor;
            }
            
            document.querySelector("input#doctors-input").value = val;
            console.log(val);

            var doctorNameBox = document.createElement("div");
            var doctorName = document.createElement("span");
            var removeButton = document.createElement("span");
            
            if (existingDoctors.length > 0){
                i = existingDoctors.length;
            }
            else{
                i=0;
            }
            
            doctorNameBox.classList.add("doctor-name-box"); //box
            
            doctorName.classList.add("doctor-name");   //name span
            var removeID ="remove_" + i;
            // doctorNameBox.setAttribute("id",removeID);

            removeButton.classList.add("remove"); //remove text
            removeButton.setAttribute("id","remove_" + i);
            console.log(removeID);
            var doctorNameText = document.createTextNode(selectedDoctor);
            var doctorRemoveText = document.createTextNode("x");

            removeButton.addEventListener("click", change_value);

            removeButton.appendChild(doctorRemoveText);



            doctorName.appendChild(doctorNameText);

            doctorNameBox.appendChild(doctorName);
            doctorNameBox.appendChild(removeButton);



            let containerDiv = document.querySelector('.by-doctors-box-spans');
            containerDiv.appendChild(doctorNameBox);
            
            document.querySelector("#remove_" + i).addEventListener('click', function(){
                let removeEl = this.parentNode;                
                containerDiv.removeChild(removeEl);
            })
            document.querySelector("#remove_"+i).addEventListener('click', change_value);
            i++;
        }
    
});