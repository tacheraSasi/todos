package main

import (
	"html/template"
	"log"
	"net/http"
	"path/filepath"
	"sync"
)

var tasks []string
var mu sync.Mutex

func main(){
	router := http.NewServeMux()

	router.HandleFunc("GET /",indexHandler)
	router.HandleFunc("POST /add",addHandler)
	router.HandleFunc("GET /add",showAddPage)

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
		log.Printf("Failed to render the template%s ERR:%v",tmplPath,err)
		return
	}

	t.Execute(w,data)
}

func indexHandler(w http.ResponseWriter, r *http.Request){
	mu.Lock()
	defer mu.Unlock()
	renderTemplate(w,"index.html",tasks)

}

func showAddPage(w http.ResponseWriter, r *http.Request){

	renderTemplate(w,"add.html",nil)
}

func addHandler(w http.ResponseWriter, r *http.Request){

	if r.Method == http.MethodPost{
		if err := r.ParseForm(); err != nil{
			http.Error(w, "Error parsing form data", http.StatusBadRequest)
			log.Println("Form parse error:", err)
			return
		}

		task := r.FormValue("task")
		mu.Lock()
		tasks = append(tasks, task)
		mu.Unlock()

		http.Redirect(w,r,"/",http.StatusSeeOther)
		return
	}


}