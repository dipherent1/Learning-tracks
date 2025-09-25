// src/lib/posts.ts
import fs from 'fs'
import path from 'path'
import matter from 'gray-matter'

// 1. Define the path to our posts directory
const postsDirectory = path.join(process.cwd(), '_posts')

export function getSortedPostsData() {
  // 2. Get file names under /_posts
  const fileNames = fs.readdirSync(postsDirectory)

  const allPostsData = fileNames.map((fileName) => {
    // 3. Remove ".md" from file name to get id
    const id = fileName.replace(/\.md$/, '')

    // 4. Read markdown file as string
    const fullPath = path.join(postsDirectory, fileName)
    const fileContents = fs.readFileSync(fullPath, 'utf8')

    // 5. Use gray-matter to parse the post metadata section
    const matterResult = matter(fileContents)

    // 6. Combine the data with the id
    return {
      id,
      ...(matterResult.data as { title: string; date: string }),
    }
  })

  // 7. Sort posts by date
  return allPostsData.sort((a, b) => {
    if (a.date < b.date) {
      return 1
    } else {
      return -1
    }
  })
}