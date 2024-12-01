import React, { useState } from "react";

function TodoApp() {
  const [todos, setTodos] = useState([]); // Array to store todos
  const [newTodo, setNewTodo] = useState(""); // Input value for the new todo

  // Function to generate a unique ID
  const generateId = () => {
    let time = new Date()
    return Math.random().toString(36).substr(2, 9)+time.toString();
  };

  // Function to add a new todo
  const addTodo = () => {
    if (newTodo.trim() === "") return; // Preventing empty todos
    const todoObject = {
      id: generateId(),
      title: newTodo,
    };
    // Spreading the old todo arra the adding the newTodo to it
    setTodos([...todos, todoObject]); // Adding the new todo to the array
    setNewTodo(""); // Clearing the input field
  };

  return (
    <div style={{ padding: "20px", maxWidth: "400px", margin: "auto" }}>
      <h2>Todo App</h2>
      <input
        type="text"
        value={newTodo}
        onChange={(e) => setNewTodo(e.target.value)}
        placeholder="Enter a new todo"
        style={{
          padding: "8px",
          width: "calc(100% - 50px)",
          marginRight: "10px",
        }}
      />
      <button onClick={addTodo} style={{ padding: "8px" }}>
        Add
      </button>
      <ul style={{ marginTop: "20px", padding: "0", listStyleType: "none" }}>
        {todos.map((todo) => (
          <li
            key={todo.id}
            style={{
              padding: "8px",
              borderBottom: "1px solid #ddd",
              textAlign: "left",
            }}
          >
            {todo.title}
          </li>
        ))}
      </ul>
    </div>
  );
}

export default TodoApp;
