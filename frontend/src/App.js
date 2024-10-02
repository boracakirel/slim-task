import React, {useEffect, useState} from 'react';
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell from '@mui/material/TableCell';
import TableContainer from '@mui/material/TableContainer';
import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';
import Paper from '@mui/material/Paper';
import axios from 'axios';

function App() {
    const [posts, setPosts] = useState([]);

    useEffect(() => {
        fetch('http://localhost:8888/posts')
            .then(response => response.json())
            .then(data => setPosts(data))
            .catch(error => console.error('Error fetching posts:', error));
    }, []);

    const deletePost = async (id) => {
        const confirmDelete = window.confirm('Are you sure want to delete?');
        if (confirmDelete) {
            try {
                await axios.delete(`http://localhost:8888/posts/${id}`);
                setPosts(posts.filter(post => post.id !== id));
            } catch (error) {
                console.error('Error deleting post:', error);
            }
        }
    };

    return (
        <div className="flex justify-center items-center min-h-screen p-4">
            <TableContainer component={Paper} className="w-full max-w-4xl shadow-lg rounded-lg">
                <Table sx={{minWidth: 650}} aria-label="simple table">
                    <TableHead>
                        <TableRow>
                            <TableCell className="font-bold">Username</TableCell>
                            <TableCell align="right" className="font-bold">Title</TableCell>
                            <TableCell align="right" className="font-bold">Body</TableCell>
                            <TableCell align="right" className="font-bold">Action</TableCell>
                        </TableRow>
                    </TableHead>
                    <TableBody>
                        {posts.length > 0 ? (
                            posts.map((post, index) => (
                                <TableRow key={index}
                                          sx={{'&:last-child td, &:last-child th': {border: 0}}}
                                >
                                    <TableCell component="th" scope="row">
                                        {post.username}
                                    </TableCell>
                                    <TableCell align="right">{post.title}</TableCell>
                                    <TableCell align="right">{post.body}</TableCell>
                                    <TableCell align="right">
                                        <button
                                            className="text-red-500 hover:text-red-700"
                                            onClick={() => deletePost(post.id)}
                                        >
                                            Delete
                                        </button>
                                    </TableCell>
                                </TableRow>
                            ))
                        ) : (
                            <tr>
                                <td colSpan="3" className="text-center py-4">No posts available</td>
                            </tr>
                        )}
                    </TableBody>
                </Table>
            </TableContainer>
        </div>
    );

}

export default App;
