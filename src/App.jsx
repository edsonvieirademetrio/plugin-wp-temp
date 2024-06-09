// App.jsx
import React, { useState, useEffect } from "react";
import {
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    Legend,
  } from "recharts";
  import "./App.scss";

export function App() {
    const [counter, setCounter] = useState(0);
    const [data, setData] = useState([]);
    const [filteredData, setFilteredData] = useState([]);
    const [range, setRange] = useState(7); // Default range is 7 days

    useEffect(() => {
        fetch('/wp-json/cdw/v1/data')
            .then(response => response.json())
            .then(data => {
                setData(data);
                setFilteredData(filterDataByRange(data, range));
            })
            .catch(error => console.error('Error fetching data:', error));
    }, []);
    useEffect(() => {
        setFilteredData(filterDataByRange(data, range));
    }, [data, range]);
    
    const filterDataByRange = (data, range) => {
        const endDate = new Date();
        const startDate = new Date();
        startDate.setDate(endDate.getDate() - range);
    
        return data.filter(item => {
          const itemDate = new Date(item.date);
          return itemDate >= startDate && itemDate <= endDate;
        });
    };
    
    // Format the date to display only the day
    const formatDay = (tickItem) => {
        // Convert the date string to a Date object
        const date = new Date(tickItem);
        // Return only the day
        return date.getDate();
    };

    // Extract the month from the data
    const formatMonth = () => {
        if (data.length === 0) return '';
        const date = new Date(data[0].date);
        return date.toLocaleString('default', { month: 'long' });
    };
    const handleRangeChange = (e) => {
        setRange(parseInt(e.target.value, 10));
    };

    return (
        <div>
            <div id="div-range-select">
                <label htmlFor="range-select">Select Range: </label>
                <select id="range-select" value={range} onChange={handleRangeChange}>
                <option value="7">Last 7 days</option>
                <option value="15">Last 15 days</option>
                <option value="30">Last 1 month</option>
                </select>
            </div>
            <BarChart
                width={385}
                height={300}
                data={filteredData}
                margin={{
                    top: 5,
                    right: 30,
                    left: 20,
                    bottom: 40,
                }}
            >
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis 
                    dataKey="date" 
                    tickFormatter={formatDay}
                    height={30}
                    tick={{ dy: 10 }} // Adjust position of day ticks
                />
                <XAxis
                    dataKey="date"
                    xAxisId="month"
                    tick={false}
                    axisLine={false}
                    height={20}
                    label={{ value: formatMonth(), position: 'insideBottom', offset: -2 }}
                />
                <YAxis />
                <Tooltip shared={false} trigger="click" />
                <Legend />
                <Bar dataKey="num_visits" fill="#8884d8" label={{offset: 10}}/>
            </BarChart>
        </div>
    );
}
