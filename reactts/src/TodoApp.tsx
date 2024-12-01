import React, { useState, useEffect } from "react";

interface Todo {
  id: string;
  title: string;
}

const TodoAppTS: React.FC = () => {
  //managing the state for todos and the new todo input
  const [todos, setTodos] = useState<Todo[]>([]);
  const [newTodo, setNewTodo] = useState<string>("");

  // setting up a connection to IndexedDB
  useEffect(() => {
    const openDB = async () => {
      const dbRequest = indexedDB.open("todoDB", 1);

      dbRequest.onupgradeneeded = (event) => {
        const db = (event.target as IDBOpenDBRequest).result;
        if (!db.objectStoreNames.contains("todos")) {
          db.createObjectStore("todos", { keyPath: "id" });
        }
      };

      dbRequest.onsuccess = () => {
        const db = dbRequest.result;
        const transaction = db.transaction("todos", "readonly");
        const store = transaction.objectStore("todos");
        const getRequest = store.getAll();

        getRequest.onsuccess = () => {
          setTodos(getRequest.result as Todo[]);
        };
      };

      dbRequest.onerror = () => {
        console.error("Failed to open the database.");
      };
    };

    openDB();
  }, []);

  // Saving todos into IndexedDB whenever the todos array changes
  useEffect(() => {
    const saveTodos = async () => {
      const dbRequest = indexedDB.open("todoDB", 1);

      dbRequest.onsuccess = () => {
        const db = dbRequest.result;
        const transaction = db.transaction("todos", "readwrite");
        const store = transaction.objectStore("todos");
        todos.forEach((todo) => store.put(todo));
      };

      dbRequest.onerror = () => {
        console.error("Failed to save the todos.");
      };
    };

    saveTodos();
  }, [todos]);

  const generateId = (): string => {
    //combining random string and timestamp to generate a random id
    let time = new Date();
    return Math.random().toString(36).substr(2, 9) + time.toString();
  };

  const addTodo = (e: React.FormEvent) => {
    e.preventDefault();
    if (newTodo.trim() === "") return; // Avoiding blank todos

    const todoObject: Todo = {
      id: generateId(),
      title: newTodo,
    };

    setTodos([...todos, todoObject]); // Adding the new todo to the list
    setNewTodo(""); // Clearing the input field
  };

  return (
    <div
      style={{
        padding: "20px",
        maxWidth: "500px",
        margin: "auto",
        fontFamily: "Arial, sans-serif",
        backgroundColor: "#f7f7f7",
        borderRadius: "8px",
        boxShadow: "0 4px 10px rgba(0, 0, 0, 0.1)",
      }}
    >
      <h2 style={{ textAlign: "center", color: "#333" }}>Todo App</h2>
      <form onSubmit={addTodo} style={{ display: "flex", marginBottom: "20px" }}>
        <input
          type="text"
          value={newTodo}
          onChange={(e) => setNewTodo(e.target.value)}
          placeholder="Enter a new todo"
          style={{
            flex: 1,
            padding: "10px",
            fontSize: "16px",
            borderRadius: "4px",
            border: "1px solid #ddd",
            marginRight: "10px",
          }}
        />
        <button
          style={{
            padding: "10px 15px",
            backgroundColor: "#4CAF50",
            color: "white",
            border: "none",
            borderRadius: "4px",
            cursor: "pointer",
          }}
        >
          Add
        </button>
      </form>
      <ul style={{ marginTop: "20px", padding: "0", listStyleType: "none" }}>
        {todos.map((todo) => (
          <li
            key={todo.id}
            style={{
              padding: "15px",
              borderBottom: "1px solid #ddd",
              borderRadius: "4px",
              backgroundColor: "#fff",
              marginBottom: "10px",
              display: "flex",
              alignItems: "center",
              justifyContent: "space-between",
            }}
          >
            <span style={{ color: "#333", fontSize: "16px" }}>{todo.title}</span>
            <button
              style={{
                padding: "5px 10px",
                backgroundColor: "#FF5733",
                color: "white",
                border: "none",
                borderRadius: "4px",
                cursor: "pointer",
              }}
              onClick={() =>
                setTodos(todos.filter((item) => item.id !== todo.id))
              }
            >
              Delete
            </button>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default TodoAppTS;
