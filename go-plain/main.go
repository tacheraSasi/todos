package main

import (
	"html/template"
	"log"
	"net/http"
	"path/filepath"
)


func main(){
	router := http.NewServeMux()

	router.HandleFunc("/",indexHandler)
	router.HandleFunc("/add",addHandler)

	log.Println("Server is running on http://localhost:3000")

	if err := http.ListenAndServe(":3000",router); err != nil{
		log.Fatal("something went wrong while running the server. ERR:",err)
	}
}

func renderTemplate(w http.ResponseWriter, tmpl string, data interface{}){
	tmplPath := filepath.Join("templates",tmpl)

	t, err := template.ParseFiles(tmplPath)
	if err != nil{
		http.Error(w,"Failed to render the template",http.StatusInternalServerError)
		log.Fatal("Failed to render the template%s ERR:%v",tmplPath,err)
	}

	t.Execute(w,data)
}

func indexHandler(w http.ResponseWriter, r *http.Request){

}


func addHandler(w http.ResponseWriter, r *http.Request){

}